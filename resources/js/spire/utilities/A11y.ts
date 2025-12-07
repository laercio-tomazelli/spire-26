import { A11yManager } from '../types';

export class A11y implements A11yManager {
  #announcer: HTMLElement | null = null;

  #getAnnouncer(): HTMLElement {
    if (!this.#announcer) {
      this.#announcer = document.createElement('div');
      this.#announcer.setAttribute('role', 'status');
      this.#announcer.setAttribute('aria-live', 'polite');
      this.#announcer.setAttribute('aria-atomic', 'true');
      this.#announcer.className = 'sr-only';
      this.#announcer.style.cssText = `
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
      `;
      document.body.appendChild(this.#announcer);
    }
    return this.#announcer;
  }

  announce(message: string, priority: 'polite' | 'assertive' = 'polite'): void {
    const announcer = this.#getAnnouncer();
    announcer.setAttribute('aria-live', priority);
    
    // Clear and set message (triggers announcement)
    announcer.textContent = '';
    requestAnimationFrame(() => {
      announcer.textContent = message;
    });
  }

  trapFocus(container: HTMLElement): () => void {
    const focusableSelector = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
    
    const handleKeydown = (e: KeyboardEvent) => {
      if (e.key !== 'Tab') return;

      const focusableElements = container.querySelectorAll<HTMLElement>(focusableSelector);
      const firstElement = focusableElements[0];
      const lastElement = focusableElements[focusableElements.length - 1];

      if (e.shiftKey && document.activeElement === firstElement) {
        e.preventDefault();
        lastElement?.focus();
      } else if (!e.shiftKey && document.activeElement === lastElement) {
        e.preventDefault();
        firstElement?.focus();
      }
    };

    container.addEventListener('keydown', handleKeydown);
    
    // Focus first element
    const firstFocusable = container.querySelector<HTMLElement>(focusableSelector);
    firstFocusable?.focus();

    // Return cleanup function
    return () => {
      container.removeEventListener('keydown', handleKeydown);
    };
  }

  skipLink(target: string, label = 'Pular para conteúdo principal'): void {
    // Check if skip link already exists
    if (document.querySelector('[data-skip-link]')) return;

    const link = document.createElement('a');
    link.href = target;
    link.textContent = label;
    link.dataset.skipLink = '';
    link.className = `
      sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 
      focus:z-50 focus:px-4 focus:py-2 focus:bg-white focus:text-black 
      focus:rounded-lg focus:shadow-lg focus:ring-2 focus:ring-blue-500
    `.trim().replace(/\s+/g, ' ');

    link.addEventListener('click', (e) => {
      e.preventDefault();
      const targetEl = document.querySelector(target);
      if (targetEl) {
        (targetEl as HTMLElement).focus();
        targetEl.scrollIntoView({ behavior: 'smooth' });
      }
    });

    document.body.insertBefore(link, document.body.firstChild);
  }
}

export const a11y = new A11y();
