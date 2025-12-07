import type { MultiSelectInstance, SelectOption } from '../types';
import { instances, emit } from '../core';

export class MultiSelect implements MultiSelectInstance {
  #el: HTMLElement;
  #trigger: HTMLElement | null;
  #dropdown: HTMLElement | null;
  #optionsList: HTMLElement | null;
  #hiddenInput: HTMLInputElement | null;
  #tagsContainer: HTMLElement | null;
  #searchInput: HTMLInputElement | null;
  #options: SelectOption[] = [];
  #selectedValues: string[] = [];
  #isOpen = false;
  #searchable: boolean;
  #maxItems: number;
  #placeholder: string;
  #boundHandleOutsideClick: (e: MouseEvent) => void;
  #boundHandleKeydown: (e: KeyboardEvent) => void;
  #boundHandleSearch: () => void;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#trigger = el.querySelector('[data-multiselect-trigger]');
    this.#dropdown = el.querySelector('[data-multiselect-dropdown]');
    this.#optionsList = el.querySelector('[data-multiselect-options]');
    this.#hiddenInput = el.querySelector('input[type="hidden"]');
    this.#tagsContainer = el.querySelector('[data-multiselect-tags]');
    this.#searchInput = el.querySelector('[data-multiselect-search]');
    
    this.#searchable = el.dataset.searchable !== 'false';
    this.#maxItems = parseInt(el.dataset.maxItems || '0') || 0;
    this.#placeholder = el.dataset.placeholder || 'Selecione...';

    this.#boundHandleOutsideClick = this.#handleOutsideClick.bind(this);
    this.#boundHandleKeydown = this.#handleKeydown.bind(this);
    this.#boundHandleSearch = this.#handleSearch.bind(this);

    this.#parseOptions();
    this.#parseInitialValues();
    this.#setupA11y();
    this.#setupListeners();
    this.#updateDisplay();
  }

  #parseOptions(): void {
    const optionEls = this.#el.querySelectorAll('[data-option]');
    this.#options = Array.from(optionEls).map(opt => ({
      value: (opt as HTMLElement).dataset.option || '',
      label: opt.textContent?.trim() || '',
      disabled: opt.hasAttribute('disabled')
    }));
  }

  #parseInitialValues(): void {
    // Parse from hidden input (comma-separated or JSON)
    if (this.#hiddenInput?.value) {
      try {
        this.#selectedValues = JSON.parse(this.#hiddenInput.value);
      } catch {
        this.#selectedValues = this.#hiddenInput.value.split(',').filter(v => v.trim());
      }
    }

    // Also check for pre-selected options
    this.#el.querySelectorAll('[data-option].selected, [data-option][aria-selected="true"]').forEach(opt => {
      const val = (opt as HTMLElement).dataset.option;
      if (val && !this.#selectedValues.includes(val)) {
        this.#selectedValues.push(val);
      }
    });
  }

  #setupA11y(): void {
    this.#trigger?.setAttribute('role', 'combobox');
    this.#trigger?.setAttribute('aria-haspopup', 'listbox');
    this.#trigger?.setAttribute('aria-expanded', 'false');
    this.#trigger?.setAttribute('aria-multiselectable', 'true');
    this.#optionsList?.setAttribute('role', 'listbox');
    this.#optionsList?.setAttribute('aria-multiselectable', 'true');

    this.#options.forEach((opt, index) => {
      const optEl = this.#el.querySelector(`[data-option="${opt.value}"]`);
      optEl?.setAttribute('role', 'option');
      optEl?.setAttribute('id', `multiselect-option-${index}`);
    });
  }

  #setupListeners(): void {
    this.#trigger?.addEventListener('click', (e) => {
      // Don't toggle if clicking on a tag remove button
      if ((e.target as HTMLElement).closest('[data-remove-tag]')) return;
      this.#isOpen ? this.close() : this.open();
    });
    
    this.#el.querySelectorAll('[data-option]').forEach(opt => {
      opt.addEventListener('click', (e) => {
        e.stopPropagation();
        const value = (opt as HTMLElement).dataset.option;
        if (value && !opt.hasAttribute('disabled')) {
          this.toggle(value);
        }
      });
    });

    // Search input
    this.#searchInput?.addEventListener('input', this.#boundHandleSearch);
    this.#searchInput?.addEventListener('click', (e) => e.stopPropagation());

    // Select all / Clear all buttons
    this.#el.querySelector('[data-select-all]')?.addEventListener('click', (e) => {
      e.stopPropagation();
      this.selectAll();
    });

    this.#el.querySelector('[data-clear-all]')?.addEventListener('click', (e) => {
      e.stopPropagation();
      this.clear();
    });
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

  #handleSearch(): void {
    const query = this.#searchInput?.value.toLowerCase() || '';
    
    this.#el.querySelectorAll('[data-option]').forEach(opt => {
      const label = opt.textContent?.toLowerCase() || '';
      const matches = label.includes(query);
      (opt as HTMLElement).style.display = matches ? '' : 'none';
    });
  }

  #updateDisplay(): void {
    // Update hidden input
    if (this.#hiddenInput) {
      this.#hiddenInput.value = JSON.stringify(this.#selectedValues);
    }

    // Update tags display
    if (this.#tagsContainer) {
      if (this.#selectedValues.length === 0) {
        this.#tagsContainer.innerHTML = `<span class="text-gray-500 dark:text-gray-400">${this.#placeholder}</span>`;
      } else {
        const tags = this.#selectedValues.map(val => {
          const opt = this.#options.find(o => o.value === val);
          return `
            <span class="inline-flex items-center gap-1 px-2 py-1 text-sm bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-lg">
              ${opt?.label || val}
              <button type="button" data-remove-tag="${val}" class="hover:text-blue-900 dark:hover:text-blue-100 transition-colors" aria-label="Remover ${opt?.label || val}">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </span>
          `;
        }).join('');
        this.#tagsContainer.innerHTML = tags;

        // Re-attach tag remove listeners
        this.#tagsContainer.querySelectorAll('[data-remove-tag]').forEach(btn => {
          btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const val = (btn as HTMLElement).dataset.removeTag;
            if (val) this.remove(val);
          });
        });
      }
    }

    // Update option checkmarks
    this.#el.querySelectorAll('[data-option]').forEach(opt => {
      const value = (opt as HTMLElement).dataset.option;
      const isSelected = value ? this.#selectedValues.includes(value) : false;
      opt.classList.toggle('selected', isSelected);
      opt.setAttribute('aria-selected', String(isSelected));
      
      // Update checkbox if exists
      const checkbox = opt.querySelector('input[type="checkbox"]') as HTMLInputElement;
      if (checkbox) {
        checkbox.checked = isSelected;
      }
    });

    // Update counter display if exists
    const counter = this.#el.querySelector('[data-multiselect-count]');
    if (counter) {
      counter.textContent = `${this.#selectedValues.length} selecionado${this.#selectedValues.length !== 1 ? 's' : ''}`;
    }
  }

  value(): string[] {
    return [...this.#selectedValues];
  }

  setValue(vals: string[]): this {
    this.#selectedValues = [...vals];
    this.#updateDisplay();
    emit(this.#el, 'multiselect:change', { values: this.#selectedValues });
    return this;
  }

  add(val: string): this {
    if (this.#maxItems > 0 && this.#selectedValues.length >= this.#maxItems) {
      emit(this.#el, 'multiselect:max-reached', { max: this.#maxItems });
      return this;
    }
    
    if (!this.#selectedValues.includes(val)) {
      this.#selectedValues.push(val);
      this.#updateDisplay();
      emit(this.#el, 'multiselect:added', { value: val, values: this.#selectedValues });
    }
    return this;
  }

  remove(val: string): this {
    const index = this.#selectedValues.indexOf(val);
    if (index > -1) {
      this.#selectedValues.splice(index, 1);
      this.#updateDisplay();
      emit(this.#el, 'multiselect:removed', { value: val, values: this.#selectedValues });
    }
    return this;
  }

  toggle(val: string): this {
    if (this.#selectedValues.includes(val)) {
      this.remove(val);
    } else {
      this.add(val);
    }
    return this;
  }

  clear(): this {
    this.#selectedValues = [];
    this.#updateDisplay();
    emit(this.#el, 'multiselect:cleared', {});
    return this;
  }

  selectAll(): this {
    const enabledOptions = this.#options
      .filter(o => !o.disabled)
      .map(o => o.value);
    
    if (this.#maxItems > 0) {
      this.#selectedValues = enabledOptions.slice(0, this.#maxItems);
    } else {
      this.#selectedValues = enabledOptions;
    }
    
    this.#updateDisplay();
    emit(this.#el, 'multiselect:select-all', { values: this.#selectedValues });
    return this;
  }

  open(): this {
    this.#dropdown?.classList.remove('hidden');
    this.#trigger?.setAttribute('aria-expanded', 'true');
    this.#isOpen = true;
    
    // Focus search input if exists
    if (this.#searchInput) {
      this.#searchInput.value = '';
      this.#handleSearch();
      requestAnimationFrame(() => this.#searchInput?.focus());
    }
    
    document.addEventListener('click', this.#boundHandleOutsideClick);
    document.addEventListener('keydown', this.#boundHandleKeydown);
    emit(this.#el, 'multiselect:opened', {});
    return this;
  }

  close(): this {
    this.#dropdown?.classList.add('hidden');
    this.#trigger?.setAttribute('aria-expanded', 'false');
    this.#isOpen = false;
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    emit(this.#el, 'multiselect:closed', {});
    return this;
  }

  options(opts: SelectOption[]): this {
    this.#options = opts;
    if (this.#optionsList) {
      this.#optionsList.innerHTML = opts.map((opt, index) => `
        <div 
          data-option="${opt.value}" 
          role="option" 
          id="multiselect-option-${index}"
          aria-selected="${this.#selectedValues.includes(opt.value)}"
          class="flex items-center gap-3 px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 ${opt.disabled ? 'opacity-50 cursor-not-allowed' : ''} ${this.#selectedValues.includes(opt.value) ? 'selected bg-blue-50 dark:bg-blue-900/30' : ''}"
          ${opt.disabled ? 'disabled' : ''}
        >
          <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" ${this.#selectedValues.includes(opt.value) ? 'checked' : ''} ${opt.disabled ? 'disabled' : ''}>
          <span>${opt.label}</span>
        </div>
      `).join('');
      
      // Re-attach listeners
      this.#el.querySelectorAll('[data-option]').forEach(opt => {
        opt.addEventListener('click', (e) => {
          e.stopPropagation();
          const value = (opt as HTMLElement).dataset.option;
          if (value && !opt.hasAttribute('disabled')) {
            this.toggle(value);
          }
        });
      });
    }
    return this;
  }

  disable(state = true): this {
    if (this.#trigger) {
      (this.#trigger as HTMLButtonElement).disabled = state;
      this.#trigger.setAttribute('aria-disabled', String(state));
    }
    return this;
  }

  destroy(): void {
    this.#searchInput?.removeEventListener('input', this.#boundHandleSearch);
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    instances.delete(this.#el);
  }
}
