import type { CarouselInstance } from '../types';
import { instances, emit } from '../core';

export class Carousel implements CarouselInstance {
  #el: HTMLElement;
  #track: HTMLElement | null;
  #slides: HTMLElement[];
  #prevBtn: HTMLElement | null;
  #nextBtn: HTMLElement | null;
  #indicators: HTMLElement[];
  #currentIndex: number = 0;
  #autoplay: boolean;
  #interval: number;
  #autoplayTimer: number | null = null;
  #loop: boolean;
  #pauseOnHover: boolean;
  #touchStartX: number = 0;
  #touchEndX: number = 0;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#track = el.querySelector('[data-carousel-track]');
    this.#slides = Array.from(el.querySelectorAll('[data-carousel-slide]'));
    this.#prevBtn = el.querySelector('[data-carousel-prev]');
    this.#nextBtn = el.querySelector('[data-carousel-next]');
    this.#indicators = Array.from(el.querySelectorAll('[data-carousel-indicator]'));
    
    this.#autoplay = el.dataset.autoplay === 'true';
    this.#interval = parseInt(el.dataset.interval || '5000');
    this.#loop = el.dataset.loop !== 'false';
    this.#pauseOnHover = el.dataset.pauseOnHover !== 'false';

    this.#setupListeners();
    this.#updateUI();

    if (this.#autoplay) {
      this.play();
    }
  }

  #setupListeners(): void {
    this.#prevBtn?.addEventListener('click', () => this.prev());
    this.#nextBtn?.addEventListener('click', () => this.next());

    this.#indicators.forEach((indicator, index) => {
      indicator.addEventListener('click', () => this.goto(index));
    });

    this.#el.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') {
        this.prev();
      } else if (e.key === 'ArrowRight') {
        this.next();
      }
    });

    this.#el.addEventListener('touchstart', (e) => {
      this.#touchStartX = e.changedTouches[0].screenX;
      if (this.#autoplay) this.pause();
    }, { passive: true });

    this.#el.addEventListener('touchend', (e) => {
      this.#touchEndX = e.changedTouches[0].screenX;
      this.#handleSwipe();
      if (this.#autoplay) this.play();
    }, { passive: true });

    if (this.#pauseOnHover && this.#autoplay) {
      this.#el.addEventListener('mouseenter', () => this.pause());
      this.#el.addEventListener('mouseleave', () => this.play());
    }
  }

  #handleSwipe(): void {
    const diff = this.#touchStartX - this.#touchEndX;
    const threshold = 50;

    if (Math.abs(diff) > threshold) {
      if (diff > 0) {
        this.next();
      } else {
        this.prev();
      }
    }
  }

  #updateUI(): void {
    this.#slides.forEach((slide, index) => {
      slide.classList.toggle('opacity-0', index !== this.#currentIndex);
      slide.classList.toggle('opacity-100', index === this.#currentIndex);
      slide.classList.toggle('z-10', index === this.#currentIndex);
      slide.classList.toggle('z-0', index !== this.#currentIndex);
      slide.setAttribute('aria-hidden', String(index !== this.#currentIndex));
    });

    this.#indicators.forEach((indicator, index) => {
      indicator.classList.toggle('bg-white', index === this.#currentIndex);
      indicator.classList.toggle('bg-white/50', index !== this.#currentIndex);
      indicator.setAttribute('aria-current', String(index === this.#currentIndex));
    });

    if (!this.#loop) {
      this.#prevBtn?.classList.toggle('opacity-50', this.#currentIndex === 0);
      this.#prevBtn?.classList.toggle('cursor-not-allowed', this.#currentIndex === 0);
      this.#nextBtn?.classList.toggle('opacity-50', this.#currentIndex === this.#slides.length - 1);
      this.#nextBtn?.classList.toggle('cursor-not-allowed', this.#currentIndex === this.#slides.length - 1);
    }

    emit(this.#el, 'carousel:change', { 
      index: this.#currentIndex, 
      total: this.#slides.length 
    });
  }

  next(): this {
    const nextIndex = this.#currentIndex + 1;
    
    if (nextIndex >= this.#slides.length) {
      if (this.#loop) {
        this.#currentIndex = 0;
      }
    } else {
      this.#currentIndex = nextIndex;
    }
    
    this.#updateUI();
    return this;
  }

  prev(): this {
    const prevIndex = this.#currentIndex - 1;
    
    if (prevIndex < 0) {
      if (this.#loop) {
        this.#currentIndex = this.#slides.length - 1;
      }
    } else {
      this.#currentIndex = prevIndex;
    }
    
    this.#updateUI();
    return this;
  }

  goto(index: number): this {
    if (index >= 0 && index < this.#slides.length) {
      this.#currentIndex = index;
      this.#updateUI();
    }
    return this;
  }

  current(): number {
    return this.#currentIndex;
  }

  play(): this {
    if (this.#autoplayTimer) {
      clearInterval(this.#autoplayTimer);
    }
    
    this.#autoplayTimer = window.setInterval(() => {
      this.next();
    }, this.#interval);
    
    this.#el.dataset.playing = 'true';
    emit(this.#el, 'carousel:play', {});
    return this;
  }

  pause(): this {
    if (this.#autoplayTimer) {
      clearInterval(this.#autoplayTimer);
      this.#autoplayTimer = null;
    }
    
    this.#el.dataset.playing = 'false';
    emit(this.#el, 'carousel:pause', {});
    return this;
  }

  destroy(): void {
    this.pause();
    instances.delete(this.#el);
  }
}
