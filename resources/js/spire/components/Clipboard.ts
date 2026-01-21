import type { ClipboardInstance } from '../types';
import { instances, emit } from '../core';

// Forward declaration for toast - will be injected via setToast
let toast: { error: (msg: string) => void } = { error: () => {} };

export function setToast(t: { error: (msg: string) => void }): void {
  toast = t;
}

export class Clipboard implements ClipboardInstance {
  #el: HTMLElement;
  #target: HTMLInputElement | HTMLTextAreaElement | null;
  #text: string;
  #successMessage: string;
  #errorMessage: string;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#target = el.dataset.clipboardTarget 
      ? document.querySelector(el.dataset.clipboardTarget) 
      : null;
    this.#text = el.dataset.clipboardText || '';
    this.#successMessage = el.dataset.successMessage || 'Copiado!';
    this.#errorMessage = el.dataset.errorMessage || 'Erro ao copiar';
    
    this.#setupListeners();
  }

  #setupListeners(): void {
    this.#el.addEventListener('click', () => this.copy());
  }

  async copy(): Promise<boolean> {
    const textToCopy = this.#target?.value || this.#text || this.#el.textContent?.trim() || '';
    return this.copyText(textToCopy);
  }

  async copyText(text: string): Promise<boolean> {
    try {
      await navigator.clipboard.writeText(text);
      
      // Visual feedback
      const originalContent = this.#el.innerHTML;
      this.#el.innerHTML = `
        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        ${this.#successMessage}
      `;
      this.#el.classList.add('text-green-600');
      
      setTimeout(() => {
        this.#el.innerHTML = originalContent;
        this.#el.classList.remove('text-green-600');
      }, 2000);
      
      emit(this.#el, 'clipboard:copied', { text });
      return true;
    } catch {
      emit(this.#el, 'clipboard:error', { text });
      toast.error(this.#errorMessage);
      return false;
    }
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
