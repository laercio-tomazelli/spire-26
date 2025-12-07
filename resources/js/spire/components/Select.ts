import type { SelectInstance, SelectOption } from '../types';
import { instances, emit } from '../core';

export class Select implements SelectInstance {
  #el: HTMLElement;
  #trigger: HTMLElement | null;
  #dropdown: HTMLElement | null;
  #optionsList: HTMLElement | null;
  #hiddenInput: HTMLInputElement | null;
  #options: SelectOption[] = [];
  #selectedValue = '';
  #isOpen = false;
  #boundHandleOutsideClick: (e: MouseEvent) => void;
  #boundHandleKeydown: (e: KeyboardEvent) => void;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#trigger = el.querySelector('[data-select-trigger]');
    this.#dropdown = el.querySelector('[data-select-dropdown]');
    this.#optionsList = el.querySelector('[data-select-options]');
    this.#hiddenInput = el.querySelector('input[type="hidden"]');

    this.#boundHandleOutsideClick = this.#handleOutsideClick.bind(this);
    this.#boundHandleKeydown = this.#handleKeydown.bind(this);

    this.#parseOptions();
    this.#setupA11y();
    this.#setupListeners();
  }

  #parseOptions(): void {
    const optionEls = this.#el.querySelectorAll('[data-option]');
    this.#options = Array.from(optionEls).map(opt => ({
      value: (opt as HTMLElement).dataset.option || '',
      label: opt.textContent?.trim() || '',
      disabled: opt.hasAttribute('disabled')
    }));

    // Set initial value
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
    const displayEl = this.#trigger?.querySelector('[data-select-value]') || this.#trigger;
    if (displayEl && selectedOpt) {
      displayEl.textContent = selectedOpt.label;
    }

    // Update option states
    this.#el.querySelectorAll('[data-option]').forEach(opt => {
      const isSelected = (opt as HTMLElement).dataset.option === this.#selectedValue;
      opt.classList.toggle('selected', isSelected);
      opt.setAttribute('aria-selected', String(isSelected));
    });
  }

  value(): string {
    return this.#selectedValue;
  }

  setValue(val: string): this {
    this.#selectedValue = val;
    if (this.#hiddenInput) {
      this.#hiddenInput.value = val;
    }
    this.#updateDisplay();
    emit(this.#el, 'select:change', { value: val });
    return this;
  }

  open(): this {
    this.#dropdown?.classList.remove('hidden');
    this.#trigger?.setAttribute('aria-expanded', 'true');
    this.#isOpen = true;
    document.addEventListener('click', this.#boundHandleOutsideClick);
    document.addEventListener('keydown', this.#boundHandleKeydown);
    emit(this.#el, 'select:opened', {});
    return this;
  }

  close(): this {
    this.#dropdown?.classList.add('hidden');
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
    instances.delete(this.#el);
  }
}
