import type { ButtonInstance } from '../types';
import { instances, emit } from '../core';

export class Button implements ButtonInstance {
  #el: HTMLButtonElement;
  #originalText: string;
  #originalClasses: string;
  #isLoading: boolean = false;

  constructor(el: HTMLButtonElement) {
    this.#el = el;
    this.#originalText = el.textContent?.trim() || '';
    this.#originalClasses = el.className;
  }

  loading(state = true): this {
    if (this.#isLoading === state) return this; // Evitar manipulação desnecessária

    this.#isLoading = state;
    if (state) {
      this.#originalText = this.#el.textContent?.trim() || '';
    }
    this.#el.disabled = state;
    this.#el.setAttribute('aria-disabled', String(state));
    this.#el.setAttribute('aria-busy', String(state));
    this.#el.textContent = state ? 'Carregando...' : this.#originalText;
    emit(this.#el, 'button:loading', { loading: state });
    return this;
  }

  success(msg = 'Salvo!', duration = 2000): this {
    this.#isLoading = false; // Reset flag
    this.#el.textContent = msg;
    this.#el.classList.add('bg-green-600');
    emit(this.#el, 'button:success', { message: msg });
    setTimeout(() => {
      this.#el.disabled = false;
      this.#el.removeAttribute('aria-disabled');
      this.#el.removeAttribute('aria-busy');
      this.#el.textContent = this.#originalText;
      this.#el.classList.remove('bg-green-600');
      emit(this.#el, 'button:reset', {});
    }, duration);
    return this;
  }

  error(msg = 'Erro!', duration = 2000): this {
    this.#isLoading = false; // Reset flag
    this.#el.textContent = msg;
    this.#el.classList.add('bg-red-600');
    emit(this.#el, 'button:error', { message: msg });
    setTimeout(() => {
      this.#el.disabled = false;
      this.#el.removeAttribute('aria-disabled');
      this.#el.removeAttribute('aria-busy');
      this.#el.textContent = this.#originalText;
      this.#el.classList.remove('bg-red-600');
      emit(this.#el, 'button:reset', {});
    }, duration);
    return this;
  }

  reset(): this {
    this.#el.disabled = false;
    this.#el.removeAttribute('aria-disabled');
    this.#el.removeAttribute('aria-busy');
    this.#el.textContent = this.#originalText;
    emit(this.#el, 'button:reset', {});
    return this;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
