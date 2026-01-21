import type { ModalInstance } from '../types';
import { instances, emit } from '../core';

export class Modal implements ModalInstance {
  #el: HTMLElement;
  #previousActiveElement: Element | null = null;
  #boundHandleKeydown: (e: KeyboardEvent) => void;
  #boundHandleClick: (e: MouseEvent) => void;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#boundHandleKeydown = this.#handleKeydown.bind(this);
    this.#boundHandleClick = this.#handleClick.bind(this);
    this.#setupA11y();
    this.#setupListeners();
  }

  #setupA11y(): void {
    this.#el.setAttribute('role', 'dialog');
    this.#el.setAttribute('aria-modal', 'true');
    this.#el.setAttribute('tabindex', '-1');
    
    const titleEl = this.#el.querySelector('[data-title]');
    if (titleEl) {
      const titleId = titleEl.id || `modal-title-${Date.now()}`;
      titleEl.id = titleId;
      this.#el.setAttribute('aria-labelledby', titleId);
    }
  }

  #setupListeners(): void {
    this.#el.querySelectorAll('[data-close]').forEach(btn => {
      btn.addEventListener('click', () => this.close());
    });
  }

  #handleKeydown(e: KeyboardEvent): void {
    if (e.key === 'Escape') {
      this.close();
      return;
    }
    
    if (e.key === 'Tab') {
      this.#trapFocus(e);
    }
  }

  #handleClick(e: MouseEvent): void {
    if (e.target === this.#el) {
      this.close();
    }
  }

  #trapFocus(e: KeyboardEvent): void {
    const focusableElements = this.#el.querySelectorAll<HTMLElement>(
      'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    
    const firstEl = focusableElements[0];
    const lastEl = focusableElements[focusableElements.length - 1];

    if (e.shiftKey && document.activeElement === firstEl) {
      e.preventDefault();
      lastEl?.focus();
    } else if (!e.shiftKey && document.activeElement === lastEl) {
      e.preventDefault();
      firstEl?.focus();
    }
  }

  #lockScroll(): void {
    const scrollY = window.scrollY;
    document.body.style.position = 'fixed';
    document.body.style.top = `-${scrollY}px`;
    document.body.style.width = '100%';
    document.body.dataset.scrollY = String(scrollY);
  }

  #unlockScroll(): void {
    const scrollY = document.body.dataset.scrollY || '0';
    document.body.style.position = '';
    document.body.style.top = '';
    document.body.style.width = '';
    window.scrollTo(0, parseInt(scrollY));
    delete document.body.dataset.scrollY;
  }

  open(): this {
    this.#previousActiveElement = document.activeElement;
    this.#el.classList.remove('hidden');
    this.#lockScroll();
    
    document.addEventListener('keydown', this.#boundHandleKeydown);
    this.#el.addEventListener('click', this.#boundHandleClick);
    
    requestAnimationFrame(() => {
      const firstFocusable = this.#el.querySelector<HTMLElement>(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
      );
      (firstFocusable || this.#el).focus();
    });
    
    emit(this.#el, 'modal:opened', {});
    return this;
  }

  close(): this {
    this.#el.classList.add('hidden');
    this.#unlockScroll();
    
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    this.#el.removeEventListener('click', this.#boundHandleClick);
    
    if (this.#previousActiveElement instanceof HTMLElement) {
      this.#previousActiveElement.focus();
    }
    
    emit(this.#el, 'modal:closed', {});
    return this;
  }

  title(text: string | HTMLElement): this {
    const titleEl = this.#el.querySelector('[data-title]');
    if (titleEl) {
      if (typeof text === 'string') {
        titleEl.textContent = text;
      } else {
        titleEl.replaceChildren(text);
      }
    }
    return this;
  }

  body(content: string | HTMLElement): this {
    const bodyEl = this.#el.querySelector('[data-body]');
    if (bodyEl) {
      if (typeof content === 'string') {
        bodyEl.innerHTML = content;
      } else {
        bodyEl.replaceChildren(content);
      }
    }
    return this;
  }

  destroy(): void {
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    this.#el.removeEventListener('click', this.#boundHandleClick);
    instances.delete(this.#el);
  }
}
