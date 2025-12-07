import type { TooltipInstance } from '../types';
import { instances } from '../core';

export class Tooltip implements TooltipInstance {
  #el: HTMLElement;
  #tooltipEl: HTMLElement | null = null;
  #content: string;
  #position: string;
  #boundShow: () => void;
  #boundHide: () => void;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#content = el.dataset.tooltip || el.getAttribute('title') || '';
    this.#position = el.dataset.tooltipPosition || 'top';
    
    el.removeAttribute('title');
    
    this.#boundShow = this.show.bind(this);
    this.#boundHide = this.hide.bind(this);
    
    this.#setupListeners();
  }

  #setupListeners(): void {
    this.#el.addEventListener('mouseenter', this.#boundShow);
    this.#el.addEventListener('mouseleave', this.#boundHide);
    this.#el.addEventListener('focus', this.#boundShow);
    this.#el.addEventListener('blur', this.#boundHide);
  }

  #createTooltip(): HTMLElement {
    const tooltip = document.createElement('div');
    tooltip.className = 'fixed z-50 px-3 py-2 text-sm text-white bg-gray-900 dark:bg-gray-700 rounded-lg shadow-lg pointer-events-none transition-opacity duration-200';
    tooltip.setAttribute('role', 'tooltip');
    tooltip.textContent = this.#content;
    return tooltip;
  }

  #positionTooltip(): void {
    if (!this.#tooltipEl) return;

    const rect = this.#el.getBoundingClientRect();
    const tooltipRect = this.#tooltipEl.getBoundingClientRect();
    const gap = 8;

    let top = 0;
    let left = 0;

    switch (this.#position) {
      case 'top':
        top = rect.top - tooltipRect.height - gap;
        left = rect.left + (rect.width - tooltipRect.width) / 2;
        break;
      case 'bottom':
        top = rect.bottom + gap;
        left = rect.left + (rect.width - tooltipRect.width) / 2;
        break;
      case 'left':
        top = rect.top + (rect.height - tooltipRect.height) / 2;
        left = rect.left - tooltipRect.width - gap;
        break;
      case 'right':
        top = rect.top + (rect.height - tooltipRect.height) / 2;
        left = rect.right + gap;
        break;
    }

    left = Math.max(8, Math.min(left, window.innerWidth - tooltipRect.width - 8));
    top = Math.max(8, Math.min(top, window.innerHeight - tooltipRect.height - 8));

    this.#tooltipEl.style.top = `${top}px`;
    this.#tooltipEl.style.left = `${left}px`;
  }

  show(): this {
    if (this.#tooltipEl || !this.#content) return this;

    this.#tooltipEl = this.#createTooltip();
    this.#tooltipEl.style.opacity = '0';
    document.body.appendChild(this.#tooltipEl);
    
    requestAnimationFrame(() => {
      this.#positionTooltip();
      if (this.#tooltipEl) {
        this.#tooltipEl.style.opacity = '1';
      }
    });

    return this;
  }

  hide(): this {
    if (this.#tooltipEl) {
      this.#tooltipEl.remove();
      this.#tooltipEl = null;
    }
    return this;
  }

  update(content: string): this {
    this.#content = content;
    if (this.#tooltipEl) {
      this.#tooltipEl.textContent = content;
      this.#positionTooltip();
    }
    return this;
  }

  destroy(): void {
    this.hide();
    this.#el.removeEventListener('mouseenter', this.#boundShow);
    this.#el.removeEventListener('mouseleave', this.#boundHide);
    this.#el.removeEventListener('focus', this.#boundShow);
    this.#el.removeEventListener('blur', this.#boundHide);
    instances.delete(this.#el);
  }
}
