import { DateRangePickerInstance } from '../types';
import { instances, emit } from '../core/registry';

export class DateRangePicker implements DateRangePickerInstance {
  #el: HTMLElement;
  #wrapper: HTMLElement;
  #startInput: HTMLInputElement;
  #endInput: HTMLInputElement;
  #picker: HTMLElement | null = null;
  #currentDate: Date;
  #startDate: Date | null = null;
  #endDate: Date | null = null;
  #minDate: Date | null = null;
  #maxDate: Date | null = null;
  #isOpen = false;
  #format: string;
  #selectingEnd = false;
  #presets: { label: string; getValue: () => [Date, Date] }[];
  #boundHandleOutsideClick: (e: MouseEvent) => void;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#wrapper = el;
    this.#wrapper.style.position = 'relative';
    this.#format = el.dataset.format || 'dd/mm/yyyy';
    
    // Create inputs container
    const inputsContainer = document.createElement('div');
    inputsContainer.className = 'flex items-center gap-2';
    
    // Start input
    this.#startInput = document.createElement('input');
    this.#startInput.type = 'text';
    this.#startInput.className = 'flex-1 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm';
    this.#startInput.placeholder = el.dataset.startPlaceholder || 'Data início';
    this.#startInput.readOnly = true;
    
    // Arrow
    const arrow = document.createElement('span');
    arrow.className = 'text-gray-400';
    arrow.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>';
    
    // End input
    this.#endInput = document.createElement('input');
    this.#endInput.type = 'text';
    this.#endInput.className = 'flex-1 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-sm';
    this.#endInput.placeholder = el.dataset.endPlaceholder || 'Data fim';
    this.#endInput.readOnly = true;
    
    inputsContainer.appendChild(this.#startInput);
    inputsContainer.appendChild(arrow);
    inputsContainer.appendChild(this.#endInput);
    el.appendChild(inputsContainer);
    
    this.#currentDate = new Date();
    
    // Parse initial values
    if (el.dataset.startValue) {
      this.#startDate = this.#parseDate(el.dataset.startValue);
      if (this.#startDate) this.#startInput.value = this.#formatDate(this.#startDate);
    }
    if (el.dataset.endValue) {
      this.#endDate = this.#parseDate(el.dataset.endValue);
      if (this.#endDate) this.#endInput.value = this.#formatDate(this.#endDate);
    }
    if (el.dataset.min) {
      this.#minDate = this.#parseDate(el.dataset.min);
    }
    if (el.dataset.max) {
      this.#maxDate = this.#parseDate(el.dataset.max);
    }
    
    // Presets
    this.#presets = [
      { label: 'Hoje', getValue: () => { const d = new Date(); return [d, d]; } },
      { label: 'Últimos 7 dias', getValue: () => { const e = new Date(); const s = new Date(); s.setDate(s.getDate() - 6); return [s, e]; } },
      { label: 'Últimos 30 dias', getValue: () => { const e = new Date(); const s = new Date(); s.setDate(s.getDate() - 29); return [s, e]; } },
      { label: 'Este mês', getValue: () => { const now = new Date(); return [new Date(now.getFullYear(), now.getMonth(), 1), new Date(now.getFullYear(), now.getMonth() + 1, 0)]; } },
      { label: 'Mês passado', getValue: () => { const now = new Date(); return [new Date(now.getFullYear(), now.getMonth() - 1, 1), new Date(now.getFullYear(), now.getMonth(), 0)]; } },
    ];
    
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
    this.#startInput.addEventListener('click', () => { this.#selectingEnd = false; this.open(); });
    this.#endInput.addEventListener('click', () => { this.#selectingEnd = true; this.open(); });
  }

  #handleOutsideClick(e: MouseEvent): void {
    if (!this.#picker?.contains(e.target as Node) && 
        e.target !== this.#startInput && 
        e.target !== this.#endInput) {
      this.close();
    }
  }

  #createPicker(): HTMLElement {
    const picker = document.createElement('div');
    picker.className = 'absolute z-50 mt-1 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4';
    picker.style.width = '600px';
    picker.innerHTML = this.#renderCalendar();
    return picker;
  }

  #renderCalendar(): string {
    const leftMonth = new Date(this.#currentDate);
    const rightMonth = new Date(this.#currentDate);
    rightMonth.setMonth(rightMonth.getMonth() + 1);
    
    const monthNames = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    const dayNames = ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'];

    let html = `
      <div class="flex gap-4">
        <!-- Presets -->
        <div class="w-32 border-r border-gray-200 dark:border-gray-700 pr-4 space-y-1">
          ${this.#presets.map((p, i) => `
            <button type="button" data-preset="${i}" class="w-full text-left px-2 py-1.5 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">
              ${p.label}
            </button>
          `).join('')}
        </div>
        
        <!-- Left Calendar -->
        <div class="flex-1">
          <div class="flex items-center justify-between mb-4">
            <button type="button" data-prev-month class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <span class="font-semibold dark:text-white">${monthNames[leftMonth.getMonth()]} ${leftMonth.getFullYear()}</span>
            <div class="w-6"></div>
          </div>
          ${this.#renderMonth(leftMonth, dayNames)}
        </div>
        
        <!-- Right Calendar -->
        <div class="flex-1">
          <div class="flex items-center justify-between mb-4">
            <div class="w-6"></div>
            <span class="font-semibold dark:text-white">${monthNames[rightMonth.getMonth()]} ${rightMonth.getFullYear()}</span>
            <button type="button" data-next-month class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
          </div>
          ${this.#renderMonth(rightMonth, dayNames)}
        </div>
      </div>
      
      <!-- Footer -->
      <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400">
          ${this.#startDate && this.#endDate 
            ? `${this.#formatDate(this.#startDate)} → ${this.#formatDate(this.#endDate)}` 
            : this.#startDate 
              ? `${this.#formatDate(this.#startDate)} → ...`
              : 'Selecione um período'}
        </div>
        <div class="flex gap-2">
          <button type="button" data-clear class="px-3 py-1.5 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
            Limpar
          </button>
          <button type="button" data-apply class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Aplicar
          </button>
        </div>
      </div>
    `;

    return html;
  }

  #renderMonth(date: Date, dayNames: string[]): string {
    const year = date.getFullYear();
    const month = date.getMonth();
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const today = new Date();

    let html = `
      <div class="grid grid-cols-7 gap-1 mb-2">
        ${dayNames.map(d => `<div class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-1">${d}</div>`).join('')}
      </div>
      <div class="grid grid-cols-7 gap-1">
    `;

    for (let i = 0; i < firstDay; i++) {
      html += `<div></div>`;
    }

    for (let day = 1; day <= daysInMonth; day++) {
      const cellDate = new Date(year, month, day);
      const isStart = this.#startDate && cellDate.toDateString() === this.#startDate.toDateString();
      const isEnd = this.#endDate && cellDate.toDateString() === this.#endDate.toDateString();
      const isInRange = this.#startDate && this.#endDate && cellDate > this.#startDate && cellDate < this.#endDate;
      const isToday = cellDate.toDateString() === today.toDateString();
      const isDisabled = (this.#minDate && cellDate < this.#minDate) || (this.#maxDate && cellDate > this.#maxDate);

      let classes = 'w-8 h-8 text-sm flex items-center justify-center transition-colors ';
      
      if (isDisabled) {
        classes += 'text-gray-300 dark:text-gray-600 cursor-not-allowed';
      } else if (isStart || isEnd) {
        classes += 'bg-blue-600 text-white font-semibold rounded-lg';
      } else if (isInRange) {
        classes += 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300';
      } else if (isToday) {
        classes += 'ring-1 ring-blue-500 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-300';
      } else {
        classes += 'hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg cursor-pointer dark:text-gray-300';
      }

      html += `<button type="button" data-day="${day}" data-month="${month}" data-year="${year}" class="${classes}" ${isDisabled ? 'disabled' : ''}>${day}</button>`;
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
        const el = btn as HTMLElement;
        const day = parseInt(el.dataset.day || '1');
        const month = parseInt(el.dataset.month || '0');
        const year = parseInt(el.dataset.year || '2024');
        const clickedDate = new Date(year, month, day);
        
        if (!this.#startDate || (this.#startDate && this.#endDate) || clickedDate < this.#startDate) {
          // Start new selection
          this.#startDate = clickedDate;
          this.#endDate = null;
          this.#startInput.value = this.#formatDate(clickedDate);
          this.#endInput.value = '';
        } else {
          // Complete selection
          this.#endDate = clickedDate;
          this.#endInput.value = this.#formatDate(clickedDate);
        }
        
        this.#picker!.innerHTML = this.#renderCalendar();
        this.#attachPickerListeners();
      });
    });

    this.#picker.querySelectorAll('[data-preset]').forEach(btn => {
      btn.addEventListener('click', () => {
        const idx = parseInt((btn as HTMLElement).dataset.preset || '0');
        const [start, end] = this.#presets[idx].getValue();
        this.#startDate = start;
        this.#endDate = end;
        this.#startInput.value = this.#formatDate(start);
        this.#endInput.value = this.#formatDate(end);
        this.#currentDate = new Date(start);
        this.#picker!.innerHTML = this.#renderCalendar();
        this.#attachPickerListeners();
      });
    });

    this.#picker.querySelector('[data-clear]')?.addEventListener('click', () => {
      this.clear();
      this.#picker!.innerHTML = this.#renderCalendar();
      this.#attachPickerListeners();
    });

    this.#picker.querySelector('[data-apply]')?.addEventListener('click', () => {
      if (this.#startDate && this.#endDate) {
        emit(this.#el, 'daterange:change', { 
          start: this.#startDate, 
          end: this.#endDate,
          startFormatted: this.#startInput.value,
          endFormatted: this.#endInput.value
        });
        this.close();
      }
    });
  }

  value(): { start: string | null; end: string | null } {
    return {
      start: this.#startInput.value || null,
      end: this.#endInput.value || null
    };
  }

  setValue(start: string | Date | null, end: string | Date | null): this {
    if (start) {
      this.#startDate = typeof start === 'string' ? this.#parseDate(start) : start;
      if (this.#startDate) this.#startInput.value = this.#formatDate(this.#startDate);
    }
    if (end) {
      this.#endDate = typeof end === 'string' ? this.#parseDate(end) : end;
      if (this.#endDate) this.#endInput.value = this.#formatDate(this.#endDate);
    }
    return this;
  }

  clear(): this {
    this.#startDate = null;
    this.#endDate = null;
    this.#startInput.value = '';
    this.#endInput.value = '';
    emit(this.#el, 'daterange:clear', {});
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
    
    emit(this.#el, 'daterange:opened', {});
    return this;
  }

  close(): this {
    if (!this.#isOpen) return this;
    
    this.#isOpen = false;
    this.#picker?.remove();
    this.#picker = null;
    
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    emit(this.#el, 'daterange:closed', {});
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
    this.#startInput.disabled = state;
    this.#endInput.disabled = state;
    if (state) this.close();
    return this;
  }

  destroy(): void {
    this.close();
    document.removeEventListener('click', this.#boundHandleOutsideClick);
    instances.delete(this.#el);
  }
}
