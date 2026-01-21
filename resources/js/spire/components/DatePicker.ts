import { DatePickerInstance } from '../types';
import { instances, emit } from '../core/registry';

export class DatePicker implements DatePickerInstance {
  #el: HTMLInputElement;
  #wrapper: HTMLElement;
  #picker: HTMLElement | null = null;
  #currentDate: Date;
  #selectedDate: Date | null = null;
  #minDate: Date | null = null;
  #maxDate: Date | null = null;
  #isOpen = false;
  #format: string;
  #boundHandleOutsideClick: (e: MouseEvent) => void;

  constructor(el: HTMLElement) {
    // If element is a div, create an input inside it
    if (el.tagName === 'DIV') {
      this.#wrapper = el;
      this.#wrapper.style.position = 'relative';
      
      const input = document.createElement('input');
      input.type = 'text';
      input.className = 'w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all';
      input.placeholder = el.dataset.placeholder || 'Selecione uma data';
      input.readOnly = true;
      if (el.dataset.value) input.value = el.dataset.value;
      el.appendChild(input);
      this.#el = input;
      
      // Copy dataset from wrapper to input for reference
      Object.assign(this.#el.dataset, el.dataset);
    } else {
      this.#el = el as HTMLInputElement;
      this.#wrapper = el.parentElement || el;
    }
    
    this.#currentDate = new Date();
    this.#format = this.#el.dataset.format || 'dd/mm/yyyy';
    
    if (this.#el.value) {
      this.#selectedDate = this.#parseDate(this.#el.value);
    }
    if (this.#el.dataset.minDate || this.#el.dataset.min) {
      this.#minDate = this.#parseDate(this.#el.dataset.minDate || this.#el.dataset.min || '');
    }
    if (this.#el.dataset.maxDate || this.#el.dataset.max) {
      this.#maxDate = this.#parseDate(this.#el.dataset.maxDate || this.#el.dataset.max || '');
    }

    this.#boundHandleOutsideClick = this.#handleOutsideClick.bind(this);
    this.#setupListeners();
    instances.set(el, this);
  }

  #parseDate(str: string): Date | null {
    const parts = str.split(/[-\/]/);
    if (parts.length === 3) {
      if (this.#format.startsWith('dd')) {
        return new Date(parseInt(parts[2]), parseInt(parts[1]) - 1, parseInt(parts[0]));
      } else {
        return new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
      }
    }
    return null;
  }

  #formatDate(date: Date): string {
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    
    if (this.#format === 'yyyy-mm-dd') return `${year}-${month}-${day}`;
    return `${day}/${month}/${year}`;
  }

  #setupListeners(): void {
    this.#el.addEventListener('focus', () => this.open());
    this.#el.addEventListener('click', () => this.open());
  }

  #handleOutsideClick(e: MouseEvent): void {
    if (!this.#picker?.contains(e.target as Node) && e.target !== this.#el) {
      this.close();
    }
  }

  #createPicker(): HTMLElement {
    const picker = document.createElement('div');
    picker.className = 'absolute z-50 mt-1 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4 w-72';
    picker.innerHTML = this.#renderCalendar();
    return picker;
  }

  #renderCalendar(): string {
    const year = this.#currentDate.getFullYear();
    const month = this.#currentDate.getMonth();
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const monthNames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    const dayNames = ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'];

    let html = `
      <div class="flex items-center justify-between mb-4">
        <button type="button" data-prev-month class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <span class="font-semibold dark:text-white">${monthNames[month]} ${year}</span>
        <button type="button" data-next-month class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
      </div>
      <div class="grid grid-cols-7 gap-1 mb-2">
        ${dayNames.map(d => `<div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-1">${d}</div>`).join('')}
      </div>
      <div class="grid grid-cols-7 gap-1">
    `;

    // Empty cells for days before first of month
    for (let i = 0; i < firstDay; i++) {
      html += `<div></div>`;
    }

    // Day cells
    const today = new Date();
    for (let day = 1; day <= daysInMonth; day++) {
      const date = new Date(year, month, day);
      const isSelected = this.#selectedDate && 
        date.getDate() === this.#selectedDate.getDate() &&
        date.getMonth() === this.#selectedDate.getMonth() &&
        date.getFullYear() === this.#selectedDate.getFullYear();
      const isToday = date.toDateString() === today.toDateString();
      const isDisabled = (this.#minDate && date < this.#minDate) || (this.#maxDate && date > this.#maxDate);

      let classes = 'w-8 h-8 rounded-lg text-sm flex items-center justify-center transition-colors ';
      if (isDisabled) {
        classes += 'text-gray-300 dark:text-gray-600 cursor-not-allowed';
      } else if (isSelected) {
        classes += 'bg-blue-600 text-white font-semibold';
      } else if (isToday) {
        classes += 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold hover:bg-blue-200 dark:hover:bg-blue-900/50 cursor-pointer';
      } else {
        classes += 'hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer dark:text-gray-300';
      }

      html += `<button type="button" data-day="${day}" class="${classes}" ${isDisabled ? 'disabled' : ''}>${day}</button>`;
    }

    html += `</div>`;
    return html;
  }

  #attachPickerListeners(): void {
    if (!this.#picker) return;

    this.#picker.querySelector('[data-prev-month]')?.addEventListener('click', () => {
      this.#currentDate.setMonth(this.#currentDate.getMonth() - 1);
      this.#picker!.innerHTML = this.#renderCalendar();
      this.#attachPickerListeners();
    });

    this.#picker.querySelector('[data-next-month]')?.addEventListener('click', () => {
      this.#currentDate.setMonth(this.#currentDate.getMonth() + 1);
      this.#picker!.innerHTML = this.#renderCalendar();
      this.#attachPickerListeners();
    });

    this.#picker.querySelectorAll('[data-day]').forEach(btn => {
      btn.addEventListener('click', () => {
        const day = parseInt((btn as HTMLElement).dataset.day || '1');
        this.#selectedDate = new Date(this.#currentDate.getFullYear(), this.#currentDate.getMonth(), day);
        this.#el.value = this.#formatDate(this.#selectedDate);
        emit(this.#el, 'datepicker:change', { date: this.#selectedDate, formatted: this.#el.value });
        this.close();
      });
    });
  }

  value(): string {
    return this.#el.value;
  }

  setValue(date: string | Date): this {
    if (typeof date === 'string') {
      this.#selectedDate = this.#parseDate(date);
    } else {
      this.#selectedDate = date;
    }
    
    if (this.#selectedDate) {
      this.#el.value = this.#formatDate(this.#selectedDate);
      this.#currentDate = new Date(this.#selectedDate);
    }
    
    return this;
  }

  open(): this {
    if (this.#isOpen) return this;
    
    this.#isOpen = true;
    this.#picker = this.#createPicker();
    
    // Position relative to wrapper
    this.#wrapper.appendChild(this.#picker);
    
    this.#attachPickerListeners();
    
    setTimeout(() => {
      document.addEventListener('click', this.#boundHandleOutsideClick);
    }, 0);
    
    emit(this.#el, 'datepicker:opened', {});
    return this;
  }

  close(): this {
    if (!this.#isOpen) return this;
    
    this.#isOpen = false;
    this.#picker?.remove();
    this.#picker = null;
    
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    emit(this.#el, 'datepicker:closed', {});
    return this;
  }

  min(date: string | Date): this {
    this.#minDate = typeof date === 'string' ? this.#parseDate(date) : date;
    return this;
  }

  max(date: string | Date): this {
    this.#maxDate = typeof date === 'string' ? this.#parseDate(date) : date;
    return this;
  }

  disable(state = true): this {
    this.#el.disabled = state;
    if (state) this.close();
    return this;
  }

  destroy(): void {
    this.close();
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    instances.delete(this.#el);
  }
}
