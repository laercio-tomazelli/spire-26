import type { TableInstance } from '../types';
import { instances, emit } from '../core';

export class Table implements TableInstance {
  #el: HTMLTableElement;
  #tbody: HTMLTableSectionElement | null;

  constructor(el: HTMLTableElement) {
    this.#el = el;
    instances.set(el, this);
    this.#tbody = el.querySelector('tbody');
  }

  loading(state = true): this {
    if (state && this.#tbody) {
      this.#tbody.innerHTML = `
        <tr>
          <td colspan="99" class="py-12 text-center text-gray-500">
            <span class="inline-flex items-center gap-2">
              <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Carregando...
            </span>
          </td>
        </tr>
      `;
      this.#el.setAttribute('aria-busy', 'true');
      emit(this.#el, 'table:loading', { loading: true });
    } else {
      this.#el.removeAttribute('aria-busy');
      emit(this.#el, 'table:loading', { loading: false });
    }
    return this;
  }

  html(rows: string): this {
    if (this.#tbody) {
      this.#tbody.innerHTML = rows;
      this.#el.removeAttribute('aria-busy');
      emit(this.#el, 'table:updated', {});
    }
    return this;
  }

  empty(message = 'Nenhum registro encontrado'): this {
    if (this.#tbody) {
      this.#tbody.innerHTML = `
        <tr>
          <td colspan="99" class="py-12 text-center text-gray-500">${message}</td>
        </tr>
      `;
      emit(this.#el, 'table:empty', { message });
    }
    return this;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
