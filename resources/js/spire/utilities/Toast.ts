import { ToastManager, ToastType } from '../types';

export class ToastManagerClass implements ToastManager {
  #config = {
    queue: [] as { element: HTMLElement; duration: number }[],
    maxVisible: 3,
    container: null as HTMLElement | null
  };

  #getContainer(): HTMLElement {
    if (!this.#config.container) {
      this.#config.container = document.createElement('div');
      this.#config.container.className = 'fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-sm';
      this.#config.container.setAttribute('role', 'region');
      this.#config.container.setAttribute('aria-label', 'Notificações');
      document.body.appendChild(this.#config.container);
    }
    return this.#config.container;
  }

  #createToast(msg: string, type: ToastType, duration: number): { element: HTMLElement; duration: number } {
    const colors: Record<ToastType, string> = {
      success: 'bg-green-600',
      error: 'bg-red-600',
      info: 'bg-blue-600',
      warning: 'bg-yellow-500 text-gray-900'
    };

    const icons: Record<ToastType, string> = {
      success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
      error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
      info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
      warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
    };

    const t = document.createElement('div');
    t.className = `${colors[type]} text-white px-4 py-3 rounded-xl shadow-2xl transform translate-x-full transition-all duration-300 flex items-center gap-3`;
    t.setAttribute('role', 'alert');
    t.setAttribute('aria-live', type === 'error' ? 'assertive' : 'polite');
    
    t.innerHTML = `
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        ${icons[type]}
      </svg>
      <span class="flex-1">${msg}</span>
      <button class="shrink-0 hover:opacity-75 transition-opacity" aria-label="Fechar notificação">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    `;

    const closeBtn = t.querySelector('button');
    closeBtn?.addEventListener('click', () => this.#removeToast(t));

    return { element: t, duration };
  }

  #removeToast(t: HTMLElement): void {
    t.classList.add('translate-x-full', 'opacity-0');
    setTimeout(() => {
      t.remove();
      this.#processQueue();
    }, 300);
  }

  #processQueue(): void {
    const container = this.#getContainer();
    const currentToasts = container.children.length;

    if (currentToasts < this.#config.maxVisible && this.#config.queue.length > 0) {
      const toastData = this.#config.queue.shift();
      if (toastData) {
        const { element, duration } = toastData;
        container.appendChild(element);
        
        requestAnimationFrame(() => {
          element.classList.remove('translate-x-full');
        });

        if (duration > 0) {
          setTimeout(() => this.#removeToast(element), duration);
        }
      }
    }
  }

  show(msg: string, type: ToastType = 'info', duration = 5000): this {
    const toastData = this.#createToast(msg, type, duration);
    this.#config.queue.push(toastData);
    this.#processQueue();
    return this;
  }

  success(msg: string, duration = 5000): this {
    return this.show(msg, 'success', duration);
  }

  error(msg: string, duration = 7000): this {
    return this.show(msg, 'error', duration);
  }

  info(msg: string, duration = 5000): this {
    return this.show(msg, 'info', duration);
  }

  warning(msg: string, duration = 6000): this {
    return this.show(msg, 'warning', duration);
  }

  clear(): void {
    this.#config.queue = [];
    if (this.#config.container) {
      this.#config.container.innerHTML = '';
    }
  }
}

export const toast = new ToastManagerClass();
