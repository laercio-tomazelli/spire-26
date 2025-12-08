import type { SelectInstance, SelectOption } from '../types';
import { instances, emit } from '../core';

export class Select implements SelectInstance {
  #el: HTMLElement;
  #trigger: HTMLElement | null;
  #dropdown: HTMLElement | null;
  #optionsList: HTMLElement | null;
  #hiddenInput: HTMLInputElement | null;
  #labelEl: HTMLElement | null;
  #chevronEl: HTMLElement | null;
  #options: SelectOption[] = [];
  #selectedValue = '';
  #placeholder = '';
  #name = '';
  #isOpen = false;
  #boundHandleOutsideClick: (e: MouseEvent) => void;
  #boundHandleKeydown: (e: KeyboardEvent) => void;
  #boundHandleReset: (e: Event) => void;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#trigger = el.querySelector('[data-select-trigger]');
    this.#dropdown = el.querySelector('[data-select-dropdown]');
    this.#optionsList = el.querySelector('[data-select-options]');
    this.#hiddenInput = el.querySelector('[data-select-input]') || el.querySelector('input[type="hidden"]');
    this.#labelEl = el.querySelector('[data-select-label]');
    this.#chevronEl = el.querySelector('[data-select-chevron]');
    this.#placeholder = el.dataset.placeholder || 'Selecione...';
    this.#name = el.dataset.name || '';

    this.#boundHandleOutsideClick = this.#handleOutsideClick.bind(this);
    this.#boundHandleKeydown = this.#handleKeydown.bind(this);
    this.#boundHandleReset = this.#handleReset.bind(this);

    this.#parseOptions();
    this.#setupA11y();
    this.#setupListeners();

    // Set initial value from hidden input
    if (this.#hiddenInput?.value) {
      this.#selectedValue = this.#hiddenInput.value;
    }
  }

  #parseOptions(): void {
    const optionEls = this.#el.querySelectorAll('[data-option]');
    this.#options = Array.from(optionEls).map(opt => ({
      value: (opt as HTMLElement).dataset.option || '',
      label: opt.textContent?.trim() || '',
      disabled: opt.hasAttribute('disabled')
    }));

    // Set initial value from selected class
    const selected = this.#el.querySelector('[data-option].selected, [data-option][aria-selected="true"]') as HTMLElement;
    if (selected) {
      this.#selectedValue = selected.dataset.option || '';
    }
  }

  #setupA11y(): void {
    this.#trigger?.setAttribute('role', 'combobox');
    this.#trigger?.setAttribute('aria-haspopup', 'listbox');
    this.#trigger?.setAttribute('aria-expanded', 'false');
    this.#optionsList?.setAttribute('role', 'listbox');

    this.#options.forEach((opt, index) => {
      const optEl = this.#el.querySelector(`[data-option="${opt.value}"]`);
      optEl?.setAttribute('role', 'option');
      optEl?.setAttribute('id', `option-${index}`);
    });
  }

  #setupListeners(): void {
    this.#trigger?.addEventListener('click', () => this.#isOpen ? this.close() : this.open());

    this.#el.querySelectorAll('[data-option]').forEach(opt => {
      opt.addEventListener('click', () => {
        const value = (opt as HTMLElement).dataset.option;
        if (value && !opt.hasAttribute('disabled')) {
          this.setValue(value);
          this.close();
        }
      });
    });

    // Listen for reset events
    window.addEventListener('select-reset', this.#boundHandleReset);
  }

  #handleReset(e: Event): void {
    const detail = (e as CustomEvent).detail;
    if (detail?.name === this.#name || detail?.name === '*') {
      this.reset();
    }
  }

  #handleOutsideClick(e: MouseEvent): void {
    if (!this.#el.contains(e.target as Node)) {
      this.close();
    }
  }

  #handleKeydown(e: KeyboardEvent): void {
    if (e.key === 'Escape') {
      this.close();
      this.#trigger?.focus();
    }
  }

  #updateDisplay(): void {
    const selectedOpt = this.#options.find(o => o.value === this.#selectedValue);

    // Update label
    if (this.#labelEl) {
      const span = this.#labelEl.querySelector('span:last-child') || this.#labelEl;
      span.textContent = selectedOpt?.label || this.#placeholder;
    }

    // Update option states
    this.#el.querySelectorAll('[data-option]').forEach(opt => {
      const isSelected = (opt as HTMLElement).dataset.option === this.#selectedValue;
      opt.classList.toggle('selected', isSelected);
      opt.classList.toggle('bg-blue-50', isSelected);
      opt.classList.toggle('dark:bg-blue-900/30', isSelected);
      opt.setAttribute('aria-selected', String(isSelected));
    });
  }

  reset(): this {
    this.#selectedValue = '';
    if (this.#hiddenInput) {
      this.#hiddenInput.value = '';
    }
    if (this.#labelEl) {
      const span = this.#labelEl.querySelector('span:last-child') || this.#labelEl;
      span.textContent = this.#placeholder;
    }
    this.#updateDisplay();
    return this;
  }

  value(): string {
    return this.#selectedValue;
  }

  setValue(val: string): this {
    this.#selectedValue = val;
    if (this.#hiddenInput) {
      this.#hiddenInput.value = val;
      this.#hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
    }
    this.#updateDisplay();

    // Emit component event
    emit(this.#el, 'select:change', { value: val, name: this.#name });

    // Also emit window event for compatibility
    window.dispatchEvent(new CustomEvent('select-change', {
      detail: { value: val, name: this.#name }
    }));

    return this;
  }

  open(): this {
    if (this.#dropdown) {
      this.#dropdown.classList.remove('hidden');
      // Trigger transition
      requestAnimationFrame(() => {
        this.#dropdown?.classList.remove('opacity-0', 'scale-95');
        this.#dropdown?.classList.add('opacity-100', 'scale-100');
      });
    }
    this.#chevronEl?.classList.add('rotate-180');
    this.#trigger?.setAttribute('aria-expanded', 'true');
    this.#isOpen = true;
    document.addEventListener('click', this.#boundHandleOutsideClick);
    document.addEventListener('keydown', this.#boundHandleKeydown);
    emit(this.#el, 'select:opened', {});
    return this;
  }

  close(): this {
    if (this.#dropdown) {
      this.#dropdown.classList.remove('opacity-100', 'scale-100');
      this.#dropdown.classList.add('opacity-0', 'scale-95');
      // Wait for transition before hiding
      setTimeout(() => {
        this.#dropdown?.classList.add('hidden');
      }, 100);
    }
    this.#chevronEl?.classList.remove('rotate-180');
    this.#trigger?.setAttribute('aria-expanded', 'false');
    this.#isOpen = false;
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    emit(this.#el, 'select:closed', {});
    return this;
  }

  options(opts: SelectOption[]): this {
    this.#options = opts;
    if (this.#optionsList) {
      this.#optionsList.innerHTML = opts.map((opt, index) => `
        <div
          data-option="${opt.value}"
          role="option"
          id="option-${index}"
          class="px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 ${opt.disabled ? 'opacity-50 cursor-not-allowed' : ''}"
          ${opt.disabled ? 'disabled' : ''}
        >
          ${opt.label}
        </div>
      `).join('');

      // Re-attach listeners
      this.#el.querySelectorAll('[data-option]').forEach(opt => {
        opt.addEventListener('click', () => {
          const value = (opt as HTMLElement).dataset.option;
          if (value && !opt.hasAttribute('disabled')) {
            this.setValue(value);
            this.close();
          }
        });
      });
    }
    return this;
  }

  disable(state = true): this {
    if (this.#trigger) {
      (this.#trigger as HTMLButtonElement).disabled = state;
      this.#trigger.setAttribute('aria-disabled', String(state));
    }
    return this;
  }

  destroy(): void {
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    window.removeEventListener('select-reset', this.#boundHandleReset);
    instances.delete(this.#el);
  }
}
