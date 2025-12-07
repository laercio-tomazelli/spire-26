import type { LazyLoadInstance } from '../types';
import { instances, emit } from '../core';

export class LazyLoad implements LazyLoadInstance {
  #el: HTMLElement;
  #src: string;
  #loaded = false;
  #observer: IntersectionObserver | null = null;
  #placeholder: string;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#src = el.dataset.src || '';
    this.#placeholder = el.dataset.placeholder || '';
    
    this.#setupObserver();
  }

  #setupObserver(): void {
    this.#observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !this.#loaded) {
          this.load();
        }
      });
    }, {
      rootMargin: '50px',
      threshold: 0.1
    });

    this.#observer.observe(this.#el);
  }

  load(): this {
    if (this.#loaded || !this.#src) return this;

    const isImage = this.#el.tagName === 'IMG';
    const isIframe = this.#el.tagName === 'IFRAME';
    const isVideo = this.#el.tagName === 'VIDEO';

    if (isImage || isIframe || isVideo) {
      (this.#el as HTMLImageElement).src = this.#src;
      
      if (isImage) {
        (this.#el as HTMLImageElement).onload = () => {
          this.#el.classList.remove('opacity-0');
          this.#el.classList.add('opacity-100', 'transition-opacity', 'duration-300');
          emit(this.#el, 'lazy:loaded', { src: this.#src });
        };
        (this.#el as HTMLImageElement).onerror = () => {
          emit(this.#el, 'lazy:error', { src: this.#src });
        };
      }
    } else {
      // For background images
      this.#el.style.backgroundImage = `url(${this.#src})`;
      emit(this.#el, 'lazy:loaded', { src: this.#src });
    }

    this.#loaded = true;
    this.#observer?.disconnect();
    
    return this;
  }

  unload(): this {
    const isImage = this.#el.tagName === 'IMG';
    
    if (isImage) {
      (this.#el as HTMLImageElement).src = this.#placeholder;
    } else {
      this.#el.style.backgroundImage = this.#placeholder ? `url(${this.#placeholder})` : 'none';
    }
    
    this.#loaded = false;
    this.#setupObserver();
    
    return this;
  }

  isLoaded(): boolean {
    return this.#loaded;
  }

  destroy(): void {
    this.#observer?.disconnect();
    instances.delete(this.#el);
  }
}
