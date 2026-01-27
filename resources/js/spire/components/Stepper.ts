import type { StepperInstance } from '../types';
import { instances, emit } from '../core';

export class Stepper implements StepperInstance {
  #el: HTMLElement;
  #steps: NodeListOf<HTMLElement>;
  #panels: NodeListOf<HTMLElement>;
  #currentStep = 1;
  #totalSteps: number;
  #completedSteps: Set<number> = new Set();
  #linear: boolean;

  constructor(el: HTMLElement) {
    this.#el = el;
    instances.set(el, this);
    this.#steps = el.querySelectorAll('[data-step]');
    this.#panels = el.querySelectorAll('[data-step-panel]');
    this.#totalSteps = this.#steps.length;
    this.#linear = el.dataset.linear !== 'false';
    this.#currentStep = parseInt(el.dataset.initialStep || '1');
    // Validate initial step
    if (this.#currentStep < 1 || this.#currentStep > this.#totalSteps) {
      this.#currentStep = 1;
    }

    this.#setupA11y();
    this.#setupListeners();
    this.#updateDisplay();
  }

  #setupA11y(): void {
    this.#el.setAttribute('role', 'navigation');
    this.#el.setAttribute('aria-label', 'Progresso');

    this.#steps.forEach((step, index) => {
      step.setAttribute('role', 'tab');
      step.setAttribute('aria-selected', String(index + 1 === this.#currentStep));
    });

    this.#panels.forEach(panel => {
      panel.setAttribute('role', 'tabpanel');
    });
  }

  #setupListeners(): void {
    this.#steps.forEach((step, index) => {
      step.addEventListener('click', () => {
        if (!this.#linear || this.canGoTo(index + 1)) {
          this.goto(index + 1);
        }
      });
    });

    // Next/Prev buttons
    this.#el.querySelectorAll('[data-step-next]').forEach(btn => {
      btn.addEventListener('click', () => this.next());
    });
    this.#el.querySelectorAll('[data-step-prev]').forEach(btn => {
      btn.addEventListener('click', () => this.prev());
    });
  }

  canGoTo(step: number): boolean {
    if (!this.#linear) return true;
    // In linear mode, can go to any previous step, or next step if current is completed
    return step <= this.#currentStep ||
      (step === this.#currentStep + 1 && this.#completedSteps.has(this.#currentStep));
  }

  #updateDisplay(): void {
    this.#steps.forEach((step, index) => {
      const stepNum = index + 1;
      const isActive = stepNum === this.#currentStep;
      const isCompleted = this.#completedSteps.has(stepNum);

      step.classList.toggle('active', isActive);
      step.classList.toggle('completed', isCompleted);
      step.setAttribute('aria-selected', String(isActive));
      step.setAttribute('aria-current', isActive ? 'step' : 'false');

      // Update step indicator
      const indicator = step.querySelector('[data-step-indicator]');
      if (indicator) {
        if (isCompleted) {
          indicator.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
          `;
        } else {
          indicator.textContent = String(stepNum);
        }
      }
    });

    this.#panels.forEach((panel, index) => {
      const isActive = index + 1 === this.#currentStep;
      panel.classList.toggle('hidden', !isActive);
      panel.setAttribute('aria-hidden', String(!isActive));
    });

    // Update nav buttons
    this.#el.querySelectorAll('[data-step-prev]').forEach(btn => {
      const button = btn as HTMLButtonElement;
      button.disabled = !this.canPrev();
    });
    this.#el.querySelectorAll('[data-step-next]').forEach(btn => {
      const button = btn as HTMLButtonElement;
      button.disabled = !this.canNext();
    });
  }

  current(): number {
    return this.#currentStep;
  }

  goto(step: number): this {
    if (step < 1 || step > this.#totalSteps) return this;
    if (this.#linear && !this.canGoTo(step)) return this;

    const prevStep = this.#currentStep;
    this.#currentStep = step;

    emit(this.#el, 'stepper:change', {
      from: prevStep,
      to: step,
      completed: Array.from(this.#completedSteps)
    });

    this.#updateDisplay();

    return this;
  }

  next(): this {
    if (this.canNext()) {
      this.complete(this.#currentStep);
      this.goto(this.#currentStep + 1);
    }
    return this;
  }

  prev(): this {
    if (this.canPrev()) {
      this.goto(this.#currentStep - 1);
    }
    return this;
  }

  complete(step?: number): this {
    const stepToComplete = step ?? this.#currentStep;
    this.#completedSteps.add(stepToComplete);
    this.#updateDisplay();

    emit(this.#el, 'stepper:completed', {
      step: stepToComplete,
      allCompleted: this.#completedSteps.size === this.#totalSteps
    });

    return this;
  }

  reset(): this {
    this.#completedSteps.clear();
    this.#currentStep = 1;
    this.#updateDisplay();
    emit(this.#el, 'stepper:reset', {});
    return this;
  }

  canNext(): boolean {
    return this.#currentStep < this.#totalSteps;
  }

  canPrev(): boolean {
    return this.#currentStep > 1;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
