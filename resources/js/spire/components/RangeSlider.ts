import type { RangeSliderInstance } from '../types';
import { instances, emit } from '../core';

export class RangeSlider implements RangeSliderInstance {
  #el: HTMLElement;
  #input: HTMLInputElement;
  #track: HTMLElement | null = null;
  #thumb: HTMLElement | null = null;
  #valueDisplay: HTMLElement | null = null;
  #minVal: number;
  #maxVal: number;
  #stepVal: number;
  #currentValue: number;

  constructor(el: HTMLElement) {
    this.#el = el;
    
    // Find or create input
    let input = el.querySelector('input[type="range"]') as HTMLInputElement | null;
    if (!input) {
      input = el.querySelector('input') as HTMLInputElement | null;
    }
    if (!input) {
      input = document.createElement('input');
      input.type = 'range';
      el.appendChild(input);
    }
    this.#input = input;
    
    this.#minVal = parseFloat(el.dataset.min || this.#input.min || '0');
    this.#maxVal = parseFloat(el.dataset.max || this.#input.max || '100');
    this.#stepVal = parseFloat(el.dataset.step || this.#input.step || '1');
    this.#currentValue = parseFloat(el.dataset.value || this.#input.value || '50');
    
    // Set input attributes
    this.#input.min = String(this.#minVal);
    this.#input.max = String(this.#maxVal);
    this.#input.step = String(this.#stepVal);
    this.#input.value = String(this.#currentValue);

    this.#createSlider();
    this.#setupListeners();
    this.#updateDisplay();
  }

  #createSlider(): void {
    this.#el.classList.add('relative', 'py-4');
    
    // Create custom track
    this.#track = document.createElement('div');
    this.#track.className = 'absolute left-0 right-0 h-2 bg-gray-200 dark:bg-gray-700 rounded-full top-1/2 -translate-y-1/2';
    
    // Create fill
    const fill = document.createElement('div');
    fill.className = 'absolute left-0 h-full bg-blue-500 rounded-full transition-all';
    fill.dataset.rangeFill = '';
    this.#track.appendChild(fill);
    
    // Create thumb
    this.#thumb = document.createElement('div');
    this.#thumb.className = 'absolute w-5 h-5 bg-white border-2 border-blue-500 rounded-full top-1/2 -translate-y-1/2 -translate-x-1/2 cursor-grab shadow-lg hover:scale-110 transition-transform';
    this.#thumb.setAttribute('tabindex', '0');
    this.#thumb.setAttribute('role', 'slider');
    this.#thumb.setAttribute('aria-valuemin', String(this.#minVal));
    this.#thumb.setAttribute('aria-valuemax', String(this.#maxVal));
    
    // Create value display
    this.#valueDisplay = document.createElement('div');
    this.#valueDisplay.className = 'absolute -top-6 left-1/2 -translate-x-1/2 text-sm font-medium text-gray-700 dark:text-gray-300 opacity-0 transition-opacity';
    this.#thumb.appendChild(this.#valueDisplay);

    // Hide original input but keep for form submission
    this.#input.style.cssText = 'position:absolute;opacity:0;pointer-events:none;';

    this.#el.appendChild(this.#track);
    this.#el.appendChild(this.#thumb);
  }

  #setupListeners(): void {
    let isDragging = false;

    const updateFromEvent = (e: MouseEvent | TouchEvent) => {
      const rect = this.#track!.getBoundingClientRect();
      const clientX = 'touches' in e ? e.touches[0].clientX : e.clientX;
      const percent = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
      const rawValue = this.#minVal + percent * (this.#maxVal - this.#minVal);
      const steppedValue = Math.round(rawValue / this.#stepVal) * this.#stepVal;
      this.setValue(steppedValue);
    };

    this.#thumb?.addEventListener('mousedown', (e) => {
      e.preventDefault();
      isDragging = true;
      this.#thumb?.classList.add('cursor-grabbing');
      this.#valueDisplay?.classList.remove('opacity-0');
      this.#valueDisplay?.classList.add('opacity-100');
    });

    document.addEventListener('mousemove', (e) => {
      if (isDragging) updateFromEvent(e);
    });

    document.addEventListener('mouseup', () => {
      if (isDragging) {
        isDragging = false;
        this.#thumb?.classList.remove('cursor-grabbing');
        this.#valueDisplay?.classList.add('opacity-0');
        this.#valueDisplay?.classList.remove('opacity-100');
      }
    });

    // Touch support
    this.#thumb?.addEventListener('touchstart', () => {
      isDragging = true;
      this.#valueDisplay?.classList.remove('opacity-0');
    });

    document.addEventListener('touchmove', (e) => {
      if (isDragging) updateFromEvent(e);
    });

    document.addEventListener('touchend', () => {
      isDragging = false;
      this.#valueDisplay?.classList.add('opacity-0');
    });

    // Keyboard support
    this.#thumb?.addEventListener('keydown', (e) => {
      const step = e.shiftKey ? this.#stepVal * 10 : this.#stepVal;
      if (e.key === 'ArrowRight' || e.key === 'ArrowUp') {
        e.preventDefault();
        this.setValue(this.#currentValue + step);
      } else if (e.key === 'ArrowLeft' || e.key === 'ArrowDown') {
        e.preventDefault();
        this.setValue(this.#currentValue - step);
      }
    });

    // Click on track
    this.#track?.addEventListener('click', updateFromEvent);
  }

  #updateDisplay(): void {
    const percent = ((this.#currentValue - this.#minVal) / (this.#maxVal - this.#minVal)) * 100;
    
    if (this.#thumb) {
      this.#thumb.style.left = `${percent}%`;
      this.#thumb.setAttribute('aria-valuenow', String(this.#currentValue));
    }
    
    const fill = this.#track?.querySelector('[data-range-fill]') as HTMLElement;
    if (fill) {
      fill.style.width = `${percent}%`;
    }
    
    if (this.#valueDisplay) {
      this.#valueDisplay.textContent = String(this.#currentValue);
    }
    
    this.#input.value = String(this.#currentValue);
  }

  value(): number {
    return this.#currentValue;
  }

  setValue(val: number | [number, number]): this {
    const numVal = Array.isArray(val) ? val[0] : val;
    this.#currentValue = Math.max(this.#minVal, Math.min(this.#maxVal, numVal));
    this.#updateDisplay();
    emit(this.#el, 'range:change', { value: this.#currentValue });
    return this;
  }

  min(val: number): this {
    this.#minVal = val;
    this.#thumb?.setAttribute('aria-valuemin', String(val));
    this.#updateDisplay();
    return this;
  }

  max(val: number): this {
    this.#maxVal = val;
    this.#thumb?.setAttribute('aria-valuemax', String(val));
    this.#updateDisplay();
    return this;
  }

  step(val: number): this {
    this.#stepVal = val;
    return this;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
