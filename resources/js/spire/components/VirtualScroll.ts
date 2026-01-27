import type { VirtualScrollInstance } from '../types';
import { instances } from '../core';

export class VirtualScroll implements VirtualScrollInstance {
  #el: HTMLElement;
  #container: HTMLElement;
  #items: unknown[] = [];
  #itemHeight: number;
  #visibleCount: number;
  #startIndex = 0;
  #renderItem: ((item: unknown, index: number) => string) | null = null;

  constructor(el: HTMLElement) {
    this.#el = el;
    instances.set(el, this);
    this.#container = document.createElement('div');
    this.#itemHeight = parseInt(el.dataset.itemHeight || '48');
    this.#visibleCount = Math.ceil(el.clientHeight / this.#itemHeight) + 5;

    this.#setupContainer();
    this.#setupListeners();
  }

  #setupContainer(): void {
    this.#el.style.overflow = 'auto';
    this.#el.style.position = 'relative';

    // Spacer for scroll height
    const spacer = document.createElement('div');
    spacer.dataset.virtualSpacer = '';
    this.#el.appendChild(spacer);

    // Visible items container
    this.#container.style.position = 'absolute';
    this.#container.style.top = '0';
    this.#container.style.left = '0';
    this.#container.style.right = '0';
    this.#el.appendChild(this.#container);
  }

  #setupListeners(): void {
    this.#el.addEventListener('scroll', () => {
      const newStartIndex = Math.max(0, Math.floor(this.#el.scrollTop / this.#itemHeight));
      const maxStartIndex = Math.max(0, this.#items.length - this.#visibleCount);
      this.#startIndex = Math.min(newStartIndex, maxStartIndex);
      this.#render();
    });
  }

  #render(): void {
    const totalHeight = this.#items.length * this.#itemHeight;
    const spacer = this.#el.querySelector('[data-virtual-spacer]') as HTMLElement;
    if (spacer) spacer.style.height = `${totalHeight}px`;

    // Clamp startIndex to valid range
    const maxStartIndex = Math.max(0, this.#items.length - this.#visibleCount);
    this.#startIndex = Math.max(0, Math.min(this.#startIndex, maxStartIndex));

    // Update container position
    this.#container.style.transform = `translateY(${this.#startIndex * this.#itemHeight}px)`;

    // Render visible items
    const endIndex = Math.min(this.#startIndex + this.#visibleCount, this.#items.length);
    const visibleItems = this.#items.slice(this.#startIndex, endIndex);

    if (this.#renderItem) {
      this.#container.innerHTML = visibleItems
        .map((item, i) => this.#renderItem!(item, this.#startIndex + i))
        .join('');
    } else {
      this.#container.innerHTML = visibleItems
        .map((item) => `
          <div style="height: ${this.#itemHeight}px" class="flex items-center px-4 border-b border-gray-200 dark:border-gray-700">
            ${String(item)}
          </div>
        `)
        .join('');
    }
  }

  setItems(items: unknown[]): this {
    this.#items = items;
    this.#startIndex = 0;
    this.#el.scrollTop = 0;
    this.#render();
    return this;
  }

  setRenderItem(fn: (item: unknown, index: number) => string): this {
    this.#renderItem = fn;
    this.#render();
    return this;
  }

  scrollTo(index: number): this {
    this.#el.scrollTop = index * this.#itemHeight;
    return this;
  }

  refresh(): this {
    this.#visibleCount = Math.ceil(this.#el.clientHeight / this.#itemHeight) + 5;
    this.#render();
    return this;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
