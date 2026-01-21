import type { DropdownInstance } from '../types';
import { instances, emit } from '../core';

export class Dropdown implements DropdownInstance {
  #el: HTMLElement;
  #menu: HTMLElement | null;
  #trigger: HTMLElement | null;
  #boundHandleOutsideClick: (e: MouseEvent) => void;
  #boundHandleKeydown: (e: KeyboardEvent) => void;
  #boundHandleTriggerClick: () => void;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#menu = el.querySelector('[data-menu]');
    this.#trigger = el.querySelector('[data-trigger]');
    
    this.#boundHandleOutsideClick = this.#handleOutsideClick.bind(this);
    this.#boundHandleKeydown = this.#handleKeydown.bind(this);
    this.#boundHandleTriggerClick = () => this.toggle();

    if (this.#menu && this.#trigger) {
      this.#setupA11y();
      this.#setupListeners();
    }
  }

  #setupA11y(): void {
    this.#trigger?.setAttribute('aria-haspopup', 'true');
    this.#trigger?.setAttribute('aria-expanded', 'false');
    this.#menu?.setAttribute('role', 'menu');
  }

  #setupListeners(): void {
    this.#trigger?.addEventListener('click', this.#boundHandleTriggerClick);
    this.#trigger?.addEventListener('keydown', this.#boundHandleKeydown);
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

  open(): this {
    this.#menu?.classList.remove('hidden');
    this.#trigger?.setAttribute('aria-expanded', 'true');
    document.addEventListener('click', this.#boundHandleOutsideClick);
    document.addEventListener('keydown', this.#boundHandleKeydown);
    emit(this.#el, 'dropdown:opened', {});
    return this;
  }

  close(): this {
    this.#menu?.classList.add('hidden');
    this.#trigger?.setAttribute('aria-expanded', 'false');
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    emit(this.#el, 'dropdown:closed', {});
    return this;
  }

  toggle(): this {
    const isOpen = !this.#menu?.classList.contains('hidden');
    return isOpen ? this.close() : this.open();
  }

  destroy(): void {
    this.#trigger?.removeEventListener('click', this.#boundHandleTriggerClick);
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    instances.delete(this.#el);
  }
}
