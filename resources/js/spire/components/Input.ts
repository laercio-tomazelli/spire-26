import type { InputInstance } from '../types';
import { instances, emit } from '../core';

export class Input implements InputInstance {
  #el: HTMLInputElement;
  #errorEl: HTMLElement | null;
  #handleInput: () => void;

  constructor(el: HTMLInputElement) {
    this.#el = el;
    this.#errorEl = el.closest('div')?.querySelector('.error-text') || null;
    this.#handleInput = () => {
      if (this.#el.classList.contains('border-red-500')) {
        this.error('');
      }
    };
    this.#setupValidation();
  }

  #setupValidation(): void {
    this.#el.addEventListener('input', this.#handleInput);
  }

  value(v: string): this {
    this.#el.value = v;
    this.#el.dispatchEvent(new Event('input', { bubbles: true }));
    emit(this.#el, 'input:change', { value: v });
    return this;
  }

  error(msg = ''): this {
    const hasError = !!msg;
    this.#el.classList.toggle('border-red-500', hasError);
    this.#el.classList.toggle('border-gray-300', !hasError);
    this.#el.setAttribute('aria-invalid', String(hasError));
    
    if (this.#errorEl) {
      this.#errorEl.textContent = msg;
      this.#errorEl.classList.toggle('hidden', !hasError);
      this.#errorEl.setAttribute('role', 'alert');
      this.#errorEl.setAttribute('aria-live', 'polite');
    }
    
    if (hasError) {
      emit(this.#el, 'input:error', { message: msg });
    }
    return this;
  }

  focus(): this {
    this.#el.focus();
    return this;
  }

  clear(): this {
    return this.value('').error('');
  }

  disable(state = true): this {
    this.#el.disabled = state;
    this.#el.setAttribute('aria-disabled', String(state));
    return this;
  }

  destroy(): void {
    this.#el.removeEventListener('input', this.#handleInput);
    instances.delete(this.#el);
  }
}
