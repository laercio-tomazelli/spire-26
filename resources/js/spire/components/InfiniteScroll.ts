import type { InfiniteScrollInstance } from '../types';
import { instances, emit } from '../core';

export class InfiniteScroll implements InfiniteScrollInstance {
  #el: HTMLElement;
  #container: HTMLElement;
  #loader: (() => Promise<string>) | null = null;
  #loading = false;
  #hasMore = true;
  #observer: IntersectionObserver | null = null;
  #sentinel: HTMLElement | null = null;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#container = el.querySelector('[data-infinite-container]') || el;
    
    this.#createSentinel();
    this.#setupObserver();
  }

  #createSentinel(): void {
    this.#sentinel = document.createElement('div');
    this.#sentinel.className = 'infinite-scroll-sentinel h-4';
    this.#sentinel.setAttribute('aria-hidden', 'true');
    this.#el.appendChild(this.#sentinel);
  }

  #setupObserver(): void {
    if (!this.#sentinel) return;

    this.#observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !this.#loading && this.#hasMore) {
          this.load();
        }
      });
    }, {
      rootMargin: '100px',
      threshold: 0
    });

    this.#observer.observe(this.#sentinel);
  }

  async load(): Promise<void> {
    if (this.#loading || !this.#hasMore || !this.#loader) return;

    this.#loading = true;
    emit(this.#el, 'infinite:loading', {});

    // Show loading indicator
    const loadingEl = document.createElement('div');
    loadingEl.className = 'infinite-scroll-loading flex justify-center py-4';
    loadingEl.innerHTML = `
      <svg class="animate-spin h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    `;
    this.#sentinel?.before(loadingEl);

    try {
      const html = await this.#loader();
      
      if (html.trim()) {
        this.#container.insertAdjacentHTML('beforeend', html);
        emit(this.#el, 'infinite:loaded', {});
      } else {
        this.#hasMore = false;
        emit(this.#el, 'infinite:end', {});
      }
    } catch (error) {
      emit(this.#el, 'infinite:error', { error });
    } finally {
      loadingEl.remove();
      this.#loading = false;
    }
  }

  reset(): this {
    this.#container.innerHTML = '';
    this.#hasMore = true;
    this.#loading = false;
    
    if (this.#sentinel) {
      this.#el.appendChild(this.#sentinel);
    }
    
    return this;
  }

  setLoader(fn: () => Promise<string>): this {
    this.#loader = fn;
    return this;
  }

  hasMore(): boolean {
    return this.#hasMore;
  }

  destroy(): void {
    this.#observer?.disconnect();
    this.#sentinel?.remove();
    instances.delete(this.#el);
  }
}
