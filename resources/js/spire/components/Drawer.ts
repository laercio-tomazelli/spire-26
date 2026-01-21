import type { DrawerInstance } from '../types';
import { instances, emit } from '../core';

export class Drawer implements DrawerInstance {
  #el: HTMLElement;
  #overlay: HTMLElement | null;
  #content: HTMLElement | null;
  #position: 'left' | 'right' | 'top' | 'bottom';
  #duration: number;
  #isOpen = false;
  #previousActiveElement: Element | null = null;
  #boundHandleKeydown: (e: KeyboardEvent) => void;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#overlay = el.querySelector('[data-drawer-overlay]');
    this.#content = el.querySelector('[data-drawer-content]');
    this.#position = (el.dataset.position as 'left' | 'right' | 'top' | 'bottom') || 'left';
    this.#duration = parseInt(el.dataset.duration || '400');
    
    this.#boundHandleKeydown = this.#handleKeydown.bind(this);
    this.#setupA11y();
    this.#setupListeners();
  }

  #setupA11y(): void {
    this.#el.setAttribute('role', 'dialog');
    this.#el.setAttribute('aria-modal', 'true');
    this.#content?.setAttribute('tabindex', '-1');
  }

  #setupListeners(): void {
    // Overlay click
    this.#overlay?.addEventListener('click', () => this.close());
    
    // Close buttons
    this.#el.querySelectorAll('[data-drawer-close]').forEach(btn => {
      btn.addEventListener('click', () => this.close());
    });

    // External triggers
    document.querySelectorAll(`[data-drawer-toggle="${this.#el.id}"]`).forEach(btn => {
      btn.addEventListener('click', () => this.toggle());
    });
    document.querySelectorAll(`[data-drawer-open="${this.#el.id}"]`).forEach(btn => {
      btn.addEventListener('click', () => this.open());
    });
  }

  #handleKeydown(e: KeyboardEvent): void {
    if (e.key === 'Escape') {
      this.close();
    }
  }

  #getTranslateClass(): { hidden: string; visible: string } {
    const translations: Record<string, { hidden: string; visible: string }> = {
      left: { hidden: '-translate-x-full', visible: 'translate-x-0' },
      right: { hidden: 'translate-x-full', visible: 'translate-x-0' },
      top: { hidden: '-translate-y-full', visible: 'translate-y-0' },
      bottom: { hidden: 'translate-y-full', visible: 'translate-y-0' }
    };
    return translations[this.#position];
  }

  open(): this {
    if (this.#isOpen) return this;
    
    this.#previousActiveElement = document.activeElement;
    this.#isOpen = true;
    
    // Show overlay
    this.#el.classList.remove('hidden', 'pointer-events-none');
    this.#overlay?.classList.remove('opacity-0');
    this.#overlay?.classList.add('opacity-100');
    
    // Slide in content
    const translate = this.#getTranslateClass();
    this.#content?.classList.remove(translate.hidden);
    this.#content?.classList.add(translate.visible);
    
    // Lock scroll
    document.body.style.overflow = 'hidden';
    
    // Focus content
    requestAnimationFrame(() => this.#content?.focus());
    
    document.addEventListener('keydown', this.#boundHandleKeydown);
    emit(this.#el, 'drawer:opened', { position: this.#position });
    
    return this;
  }

  close(): this {
    if (!this.#isOpen) return this;
    
    this.#isOpen = false;
    
    // Slide out content
    const translate = this.#getTranslateClass();
    this.#content?.classList.remove(translate.visible);
    this.#content?.classList.add(translate.hidden);
    
    // Fade out overlay
    this.#overlay?.classList.remove('opacity-100');
    this.#overlay?.classList.add('opacity-0');
    
    // Hide after animation
    setTimeout(() => {
      if (!this.#isOpen) {
        this.#el.classList.add('hidden', 'pointer-events-none');
      }
    }, this.#duration);
    
    // Unlock scroll
    document.body.style.overflow = '';
    
    // Restore focus
    if (this.#previousActiveElement instanceof HTMLElement) {
      this.#previousActiveElement.focus();
    }
    
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    emit(this.#el, 'drawer:closed', { position: this.#position });
    
    return this;
  }

  toggle(): this {
    return this.#isOpen ? this.close() : this.open();
  }

  isOpen(): boolean {
    return this.#isOpen;
  }

  destroy(): void {
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    instances.delete(this.#el);
  }
}
