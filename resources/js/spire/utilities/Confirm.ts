import { ConfirmOptions } from '../types';

export const confirm = (options: ConfirmOptions): Promise<boolean> => {
  return new Promise((resolve) => {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm';
    modal.setAttribute('role', 'alertdialog');
    modal.setAttribute('aria-modal', 'true');
    modal.setAttribute('aria-labelledby', 'confirm-title');
    modal.setAttribute('aria-describedby', 'confirm-message');

    const confirmClass = options.confirmClass || 'bg-gradient-to-r from-blue-600 to-purple-600';

    modal.innerHTML = `
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all">
        <h3 id="confirm-title" class="text-xl font-bold mb-4 dark:text-white">${options.title || 'Confirmar'}</h3>
        <p id="confirm-message" class="text-gray-600 dark:text-gray-300 mb-6">${options.message}</p>
        <div class="flex gap-3 justify-end">
          <button data-cancel class="px-6 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            ${options.cancelText || 'Cancelar'}
          </button>
          <button data-confirm class="px-6 py-2.5 rounded-xl text-white ${confirmClass} hover:opacity-90 transition-opacity">
            ${options.confirmText || 'Confirmar'}
          </button>
        </div>
      </div>
    `;

    const cleanup = (result: boolean) => {
      modal.remove();
      resolve(result);
      if (result && options.onConfirm) options.onConfirm();
      if (!result && options.onCancel) options.onCancel();
    };

    modal.querySelector('[data-confirm]')?.addEventListener('click', () => cleanup(true));
    modal.querySelector('[data-cancel]')?.addEventListener('click', () => cleanup(false));
    modal.addEventListener('click', (e) => {
      if (e.target === modal) cleanup(false);
    });
    
    document.addEventListener('keydown', function handler(e) {
      if (e.key === 'Escape') {
        cleanup(false);
        document.removeEventListener('keydown', handler);
      }
    });

    document.body.appendChild(modal);
    
    // Focus confirm button
    requestAnimationFrame(() => {
      modal.querySelector<HTMLButtonElement>('[data-confirm]')?.focus();
    });
  });
};
