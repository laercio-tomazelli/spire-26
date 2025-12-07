import type { ColorPickerInstance } from '../types';
import { instances, emit } from '../core';

export class ColorPicker implements ColorPickerInstance {
  #el: HTMLInputElement;
  #wrapper: HTMLElement;
  #picker: HTMLElement | null = null;
  #isOpen = false;
  #currentColor: string;
  #presets: string[];
  #boundHandleOutsideClick: (e: MouseEvent) => void;

  constructor(el: HTMLElement) {
    // If element is a div, create an input inside it
    if (el.tagName === 'DIV') {
      this.#wrapper = el;
      this.#wrapper.style.position = 'relative';
      
      const input = document.createElement('input');
      input.type = 'text';
      input.className = 'w-full px-4 py-3 pl-12 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-mono';
      input.placeholder = '#000000';
      input.value = el.dataset.value || '#3B82F6';
      el.appendChild(input);
      this.#el = input;
      
      // Copy presets from wrapper
      if (el.dataset.presets) {
        this.#el.dataset.presets = el.dataset.presets;
      }
    } else {
      this.#el = el as HTMLInputElement;
      this.#wrapper = el.parentElement || el;
      this.#wrapper.style.position = 'relative';
    }
    
    this.#currentColor = this.#el.value || '#3B82F6';
    
    // Parse presets - can be comma-separated or JSON array
    const presetsData = this.#el.dataset.presets || '';
    if (presetsData.startsWith('[')) {
      try {
        this.#presets = JSON.parse(presetsData);
      } catch {
        this.#presets = this.#getDefaultPresets();
      }
    } else {
      this.#presets = presetsData.split(',').filter(c => c.trim()) || this.#getDefaultPresets();
    }
    
    if (this.#presets.length === 0) {
      this.#presets = this.#getDefaultPresets();
    }

    this.#boundHandleOutsideClick = this.#handleOutsideClick.bind(this);
    this.#setupPreview();
    this.#setupListeners();
  }
  
  #getDefaultPresets(): string[] {
    return [
      '#EF4444', '#F97316', '#F59E0B', '#EAB308', '#84CC16', '#22C55E',
      '#10B981', '#14B8A6', '#06B6D4', '#0EA5E9', '#3B82F6', '#6366F1',
      '#8B5CF6', '#A855F7', '#D946EF', '#EC4899', '#F43F5E', '#000000',
      '#6B7280', '#FFFFFF'
    ];
  }

  #setupPreview(): void {
    const preview = document.createElement('div');
    preview.className = 'absolute left-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-lg border border-gray-300 dark:border-gray-600 cursor-pointer';
    preview.style.backgroundColor = this.#currentColor;
    preview.dataset.colorPreview = '';
    
    this.#wrapper.insertBefore(preview, this.#el);
  }

  #setupListeners(): void {
    this.#el.addEventListener('focus', () => this.open());
    this.#wrapper.querySelector('[data-color-preview]')?.addEventListener('click', () => this.open());
    
    this.#el.addEventListener('input', () => {
      const color = this.#el.value;
      if (/^#[0-9A-Fa-f]{6}$/.test(color)) {
        this.#currentColor = color;
        this.#updatePreview();
      }
    });
  }

  #handleOutsideClick(e: MouseEvent): void {
    if (!this.#picker?.contains(e.target as Node) && e.target !== this.#el) {
      this.close();
    }
  }

  #updatePreview(): void {
    const preview = this.#wrapper.querySelector('[data-color-preview]') as HTMLElement;
    if (preview) {
      preview.style.backgroundColor = this.#currentColor;
    }
  }

  #createPicker(): HTMLElement {
    const picker = document.createElement('div');
    picker.className = 'absolute z-50 mt-1 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4 w-64';
    
    picker.innerHTML = `
      <div class="grid grid-cols-5 gap-2 mb-4">
        ${this.#presets.map(color => `
          <button type="button" data-color="${color}" 
            class="w-8 h-8 rounded-lg border-2 transition-transform hover:scale-110 ${color === this.#currentColor ? 'border-blue-500 ring-2 ring-blue-200' : 'border-transparent'}"
            style="background-color: ${color}">
          </button>
        `).join('')}
      </div>
      <div class="flex gap-2">
        <input type="color" value="${this.#currentColor}" class="w-10 h-10 rounded-lg cursor-pointer border-0 p-0">
        <input type="text" value="${this.#currentColor}" 
          class="flex-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-mono dark:text-white"
          pattern="^#[0-9A-Fa-f]{6}$">
      </div>
    `;
    
    return picker;
  }

  #attachPickerListeners(): void {
    if (!this.#picker) return;

    // Preset colors
    this.#picker.querySelectorAll('[data-color]').forEach(btn => {
      btn.addEventListener('click', () => {
        const color = (btn as HTMLElement).dataset.color || '';
        this.setValue(color);
        this.close();
      });
    });

    // Native color input
    const colorInput = this.#picker.querySelector('input[type="color"]') as HTMLInputElement;
    colorInput?.addEventListener('input', () => {
      this.setValue(colorInput.value);
    });

    // Text input
    const textInput = this.#picker.querySelector('input[type="text"]') as HTMLInputElement;
    textInput?.addEventListener('input', () => {
      if (/^#[0-9A-Fa-f]{6}$/.test(textInput.value)) {
        this.setValue(textInput.value);
      }
    });
  }

  value(): string {
    return this.#currentColor;
  }

  setValue(color: string): this {
    this.#currentColor = color;
    this.#el.value = color;
    this.#updatePreview();
    emit(this.#el, 'colorpicker:change', { color });
    return this;
  }

  open(): this {
    if (this.#isOpen) return this;
    
    this.#isOpen = true;
    this.#picker = this.#createPicker();
    
    this.#wrapper.appendChild(this.#picker);
    
    this.#attachPickerListeners();
    
    setTimeout(() => {
      document.addEventListener('click', this.#boundHandleOutsideClick);
    }, 0);
    
    emit(this.#el, 'colorpicker:opened', {});
    return this;
  }

  close(): this {
    if (!this.#isOpen) return this;
    
    this.#isOpen = false;
    this.#picker?.remove();
    this.#picker = null;
    
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    emit(this.#el, 'colorpicker:closed', {});
    return this;
  }

  destroy(): void {
    this.close();
    instances.delete(this.#el);
  }
}
