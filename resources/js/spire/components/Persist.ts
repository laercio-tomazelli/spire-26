import type { PersistInstance } from '../types';
import { instances, emit } from '../core';

export class Persist implements PersistInstance {
  #el: HTMLElement;
  #key: string;
  #storage: Storage;
  #properties: string[];

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#key = el.dataset.persistKey || `vp-${el.id || Date.now()}`;
    this.#storage = el.dataset.persistSession === 'true' ? sessionStorage : localStorage;
    this.#properties = (el.dataset.persist || 'value').split(',').map(p => p.trim());

    instances.set(el, this);
    this.load();
    this.#setupAutoSave();
  }

  #setupAutoSave(): void {
    // Auto-save on form inputs
    if (this.#el instanceof HTMLInputElement ||
      this.#el instanceof HTMLSelectElement ||
      this.#el instanceof HTMLTextAreaElement) {
      this.#el.addEventListener('change', () => this.save());
      this.#el.addEventListener('input', () => this.save());
    }

    // Save before page unload
    window.addEventListener('beforeunload', () => this.save());
  }

  save(): this {
    const data: Record<string, unknown> = {};

    this.#properties.forEach(prop => {
      if (prop === 'value' && 'value' in this.#el) {
        data.value = (this.#el as HTMLInputElement).value;
      } else if (prop === 'checked' && 'checked' in this.#el) {
        data.checked = (this.#el as HTMLInputElement).checked;
      } else if (prop === 'innerHTML') {
        data.innerHTML = this.#el.innerHTML;
      } else if (prop === 'class') {
        data.class = this.#el.className;
      } else if (this.#el.dataset[prop]) {
        data[prop] = this.#el.dataset[prop];
      }
    });

    this.#storage.setItem(this.#key, JSON.stringify(data));
    emit(this.#el, 'persist:saved', { key: this.#key, data });
    return this;
  }

  load(): this {
    const stored = this.#storage.getItem(this.#key);
    if (!stored) return this;

    try {
      const data = JSON.parse(stored) as Record<string, unknown>;

      this.#properties.forEach(prop => {
        if (prop === 'value' && 'value' in this.#el && data.value !== undefined) {
          (this.#el as HTMLInputElement).value = String(data.value);
        } else if (prop === 'checked' && 'checked' in this.#el && data.checked !== undefined) {
          (this.#el as HTMLInputElement).checked = Boolean(data.checked);
        } else if (prop === 'innerHTML' && data.innerHTML !== undefined) {
          this.#el.innerHTML = String(data.innerHTML);
        } else if (prop === 'class' && data.class !== undefined) {
          this.#el.className = String(data.class);
        } else if (data[prop] !== undefined) {
          this.#el.dataset[prop] = String(data[prop]);
        }
      });

      emit(this.#el, 'persist:loaded', { key: this.#key, data });
    } catch {
      // Invalid JSON, ignore
    }

    return this;
  }

  clear(): this {
    this.#storage.removeItem(this.#key);
    emit(this.#el, 'persist:cleared', { key: this.#key });
    return this;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
