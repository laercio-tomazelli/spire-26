import type { SkeletonInstance } from '../types';
import { instances, emit } from '../core';

export class Skeleton implements SkeletonInstance {
  #el: HTMLElement;
  #target: HTMLElement | null;

  constructor(el: HTMLElement) {
    this.#el = el;
    instances.set(el, this);
    this.#target = el.dataset.target ? document.querySelector(el.dataset.target) : null;
  }

  show(): this {
    this.#el.classList.remove('hidden');
    if (this.#target) {
      this.#target.classList.add('hidden');
    }
    emit(this.#el, 'skeleton:shown', {});
    return this;
  }

  hide(): this {
    this.#el.classList.add('hidden');
    if (this.#target) {
      this.#target.classList.remove('hidden');
    }
    emit(this.#el, 'skeleton:hidden', {});
    return this;
  }

  toggle(): this {
    return this.#el.classList.contains('hidden') ? this.show() : this.hide();
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
