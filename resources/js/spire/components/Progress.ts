import type { ProgressInstance } from '../types';
import { instances, emit } from '../core';

export class Progress implements ProgressInstance {
  #el: HTMLElement;
  #bar: HTMLElement | null;
  #label: HTMLElement | null;
  #currentValue = 0;
  #max = 100;
  #isIndeterminate = false;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#bar = el.querySelector('[data-progress-bar]');
    this.#label = el.querySelector('[data-progress-label]');
    this.#max = parseInt(el.dataset.max || '100');
    this.#currentValue = parseInt(el.dataset.value || '0');
    
    this.#setupA11y();
    this.#updateDisplay();
  }

  #setupA11y(): void {
    this.#el.setAttribute('role', 'progressbar');
    this.#el.setAttribute('aria-valuemin', '0');
    this.#el.setAttribute('aria-valuemax', String(this.#max));
    this.#el.setAttribute('aria-valuenow', String(this.#currentValue));
  }

  #updateDisplay(): void {
    if (this.#isIndeterminate) {
      this.#el.removeAttribute('aria-valuenow');
      this.#bar?.classList.add('animate-pulse');
      if (this.#bar) this.#bar.style.width = '100%';
      if (this.#label) this.#label.textContent = '';
      return;
    }

    this.#bar?.classList.remove('animate-pulse');
    const percent = Math.min(100, Math.max(0, (this.#currentValue / this.#max) * 100));
    
    if (this.#bar) {
      this.#bar.style.width = `${percent}%`;
      this.#bar.style.transition = 'width 0.3s ease-out';
    }
    
    if (this.#label) {
      this.#label.textContent = `${Math.round(percent)}%`;
    }
    
    this.#el.setAttribute('aria-valuenow', String(this.#currentValue));
    emit(this.#el, 'progress:change', { value: this.#currentValue, percent });
  }

  value(percent?: number): number | this {
    if (percent === undefined) return this.#currentValue;
    
    this.#currentValue = Math.min(this.#max, Math.max(0, percent));
    this.#isIndeterminate = false;
    this.#updateDisplay();
    return this;
  }

  increment(amount = 10): this {
    return this.value(this.#currentValue + amount) as this;
  }

  decrement(amount = 10): this {
    return this.value(this.#currentValue - amount) as this;
  }

  complete(): this {
    this.#currentValue = this.#max;
    this.#isIndeterminate = false;
    this.#updateDisplay();
    emit(this.#el, 'progress:complete', {});
    return this;
  }

  reset(): this {
    this.#currentValue = 0;
    this.#isIndeterminate = false;
    this.#updateDisplay();
    emit(this.#el, 'progress:reset', {});
    return this;
  }

  indeterminate(state = true): this {
    this.#isIndeterminate = state;
    this.#updateDisplay();
    return this;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
