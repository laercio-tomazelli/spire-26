import type { PopoverInstance } from '../types';
import { instances, emit } from '../core';

export class Popover implements PopoverInstance {
  #el: HTMLElement;
  #trigger: HTMLElement | null;
  #content: HTMLElement | null;
  #position: 'top' | 'bottom' | 'left' | 'right';
  #isOpen = false;
  #boundHandleOutsideClick: (e: MouseEvent) => void;
  #boundHandleKeydown: (e: KeyboardEvent) => void;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#trigger = el.querySelector('[data-popover-trigger]');
    this.#content = el.querySelector('[data-popover-content]');
    this.#position = (el.dataset.position as 'top' | 'bottom' | 'left' | 'right') || 'bottom';
    
    this.#boundHandleOutsideClick = this.#handleOutsideClick.bind(this);
    this.#boundHandleKeydown = this.#handleKeydown.bind(this);
    
    this.#setupA11y();
    this.#setupListeners();
  }

  #setupA11y(): void {
    this.#trigger?.setAttribute('aria-haspopup', 'true');
    this.#trigger?.setAttribute('aria-expanded', 'false');
    this.#content?.setAttribute('role', 'dialog');
  }

  #setupListeners(): void {
    this.#trigger?.addEventListener('click', () => this.toggle());
  }

  #handleOutsideClick(e: MouseEvent): void {
    if (!this.#el.contains(e.target as Node)) {
      this.hide();
    }
  }

  #handleKeydown(e: KeyboardEvent): void {
    if (e.key === 'Escape') {
      this.hide();
      this.#trigger?.focus();
    }
  }

  #positionContent(): void {
    if (!this.#content || !this.#trigger) return;
    
    // Reset position
    this.#content.style.top = '';
    this.#content.style.bottom = '';
    this.#content.style.left = '';
    this.#content.style.right = '';
    
    const gap = 8;
    
    switch (this.#position) {
      case 'top':
        this.#content.style.bottom = '100%';
        this.#content.style.left = '50%';
        this.#content.style.transform = 'translateX(-50%)';
        this.#content.style.marginBottom = `${gap}px`;
        break;
      case 'bottom':
        this.#content.style.top = '100%';
        this.#content.style.left = '50%';
        this.#content.style.transform = 'translateX(-50%)';
        this.#content.style.marginTop = `${gap}px`;
        break;
      case 'left':
        this.#content.style.right = '100%';
        this.#content.style.top = '50%';
        this.#content.style.transform = 'translateY(-50%)';
        this.#content.style.marginRight = `${gap}px`;
        break;
      case 'right':
        this.#content.style.left = '100%';
        this.#content.style.top = '50%';
        this.#content.style.transform = 'translateY(-50%)';
        this.#content.style.marginLeft = `${gap}px`;
        break;
    }
  }

  show(): this {
    if (this.#isOpen) return this;
    
    this.#isOpen = true;
    this.#positionContent();
    this.#content?.classList.remove('hidden', 'opacity-0', 'scale-95');
    this.#content?.classList.add('opacity-100', 'scale-100');
    this.#trigger?.setAttribute('aria-expanded', 'true');
    
    setTimeout(() => {
      document.addEventListener('click', this.#boundHandleOutsideClick);
      document.addEventListener('keydown', this.#boundHandleKeydown);
    }, 0);
    
    emit(this.#el, 'popover:shown', { position: this.#position });
    return this;
  }

  hide(): this {
    if (!this.#isOpen) return this;
    
    this.#isOpen = false;
    this.#content?.classList.add('opacity-0', 'scale-95');
    this.#content?.classList.remove('opacity-100', 'scale-100');
    
    setTimeout(() => {
      if (!this.#isOpen) {
        this.#content?.classList.add('hidden');
      }
    }, 150);
    
    this.#trigger?.setAttribute('aria-expanded', 'false');
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    
    emit(this.#el, 'popover:hidden', {});
    return this;
  }

  toggle(): this {
    return this.#isOpen ? this.hide() : this.show();
  }

  update(content: string | HTMLElement): this {
    if (this.#content) {
      if (typeof content === 'string') {
        this.#content.innerHTML = content;
      } else {
        this.#content.replaceChildren(content);
      }
    }
    return this;
  }

  destroy(): void {
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    instances.delete(this.#el);
  }
}
