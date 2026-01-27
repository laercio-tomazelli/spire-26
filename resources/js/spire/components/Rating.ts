import type { RatingInstance } from '../types';
import { instances, emit } from '../core';

export class Rating implements RatingInstance {
  #el: HTMLElement;
  #stars: NodeListOf<HTMLElement>;
  #input: HTMLInputElement | null;
  #valueDisplay: HTMLElement | null;
  #max: number;
  #half: boolean;
  #readonly: boolean;
  #currentValue: number;

  constructor(el: HTMLElement) {
    this.#el = el;
    instances.set(el, this);

    this.#stars = el.querySelectorAll('[data-rating-star]');
    this.#input = el.querySelector('[data-rating-input]');
    this.#valueDisplay = el.querySelector('[data-rating-value-display]');
    this.#max = parseInt(el.dataset.max || '5');
    this.#half = el.dataset.half === 'true';
    this.#readonly = el.dataset.readonly === 'true';
    this.#currentValue = parseFloat(el.dataset.value || '0');

    if (!this.#readonly) {
      this.#setupListeners();
    }
  }

  #setupListeners(): void {
    this.#stars.forEach((star, index) => {
      const starValue = index + 1;

      star.addEventListener('mouseenter', (e) => {
        if (this.#half) {
          const rect = star.getBoundingClientRect();
          const isHalf = (e as MouseEvent).clientX < rect.left + rect.width / 2;
          this.#preview(isHalf ? starValue - 0.5 : starValue);
        } else {
          this.#preview(starValue);
        }
      });

      star.addEventListener('mousemove', (e) => {
        if (this.#half) {
          const rect = star.getBoundingClientRect();
          const isHalf = (e as MouseEvent).clientX < rect.left + rect.width / 2;
          this.#preview(isHalf ? starValue - 0.5 : starValue);
        }
      });

      star.addEventListener('click', (e) => {
        if (this.#half) {
          const rect = star.getBoundingClientRect();
          const isHalf = (e as MouseEvent).clientX < rect.left + rect.width / 2;
          this.setValue(isHalf ? starValue - 0.5 : starValue);
        } else {
          this.setValue(starValue);
        }
      });
    });

    this.#el.addEventListener('mouseleave', () => {
      this.#render(this.#currentValue);
    });
  }

  #preview(val: number): void {
    this.#render(val);
  }

  #render(val: number): void {
    this.#stars.forEach((star, index) => {
      const starValue = index + 1;
      const fill = star.querySelector('[data-rating-fill]') as HTMLElement;

      if (fill) {
        let clipPercent = '100%';
        if (starValue <= val) {
          clipPercent = '0%';
        } else if (starValue - 0.5 <= val && this.#half) {
          clipPercent = '50%';
        }
        fill.style.clipPath = `inset(0 ${clipPercent} 0 0)`;
      }
    });

    if (this.#valueDisplay) {
      this.#valueDisplay.textContent = this.#half ? val.toFixed(1) : val.toString();
    }
  }

  value(): number {
    return this.#currentValue;
  }

  setValue(val: number): this {
    this.#currentValue = Math.max(0, Math.min(val, this.#max));
    this.#el.dataset.value = String(this.#currentValue);

    if (this.#input) {
      this.#input.value = String(this.#currentValue);
    }

    this.#render(this.#currentValue);
    emit(this.#el, 'rating:change', { value: this.#currentValue });
    return this;
  }

  disable(state = true): this {
    this.#readonly = state;
    return this;
  }

  readonly(state = true): this {
    this.#readonly = state;
    return this;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
