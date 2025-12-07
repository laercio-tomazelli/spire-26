import type { CollapseInstance } from '../types';
import { instances, emit } from '../core';

export class Collapse implements CollapseInstance {
  #el: HTMLElement;
  #content: HTMLElement | null;
  #icon: HTMLElement | null;
  #isOpenState: boolean;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#content = el.querySelector('[data-collapse-content]');
    this.#icon = el.querySelector('[data-collapse-icon]');
    this.#isOpenState = this.#content ? 
      (this.#content.style.maxHeight !== '0px' && this.#content.style.maxHeight !== '') : 
      false;
    
    if (this.#isOpenState && this.#content) {
      this.#content.style.maxHeight = this.#content.scrollHeight + 'px';
      this.#content.style.opacity = '1';
    }
  }

  toggle(): this {
    if (this.#isOpenState) {
      this.close();
    } else {
      this.open();
    }
    return this;
  }

  open(): this {
    if (!this.#content) return this;
    
    this.#content.style.maxHeight = this.#content.scrollHeight + 'px';
    this.#content.style.opacity = '1';
    this.#isOpenState = true;
    
    if (this.#icon) {
      this.#icon.style.transform = 'rotate(180deg)';
    }
    
    const button = this.#el.querySelector('button');
    button?.setAttribute('aria-expanded', 'true');
    
    emit(this.#el, 'collapse:opened', { id: this.#el.dataset.collapse });
    return this;
  }

  close(): this {
    if (!this.#content) return this;
    
    this.#content.style.maxHeight = '0';
    this.#content.style.opacity = '0';
    this.#isOpenState = false;
    
    if (this.#icon) {
      this.#icon.style.transform = 'rotate(0deg)';
    }
    
    const button = this.#el.querySelector('button');
    button?.setAttribute('aria-expanded', 'false');
    
    emit(this.#el, 'collapse:closed', { id: this.#el.dataset.collapse });
    return this;
  }

  isOpen(): boolean {
    return this.#isOpenState;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
