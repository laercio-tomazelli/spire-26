import type { AccordionInstance } from '../types';
import { instances, emit } from '../core';

export class Accordion implements AccordionInstance {
  #el: HTMLElement;
  #items: NodeListOf<HTMLElement>;
  #allowMultiple: boolean;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#items = el.querySelectorAll('[data-accordion-item]');
    this.#allowMultiple = el.dataset.multiple !== 'false';
    this.#setupA11y();
    this.#setupListeners();
  }

  #setupA11y(): void {
    this.#items.forEach((item, index) => {
      const trigger = item.querySelector('[data-accordion-trigger]');
      const content = item.querySelector('[data-accordion-content]');
      
      if (trigger && content) {
        const id = item.dataset.accordionItem || `accordion-${index}`;
        trigger.setAttribute('aria-expanded', 'false');
        trigger.setAttribute('aria-controls', `content-${id}`);
        trigger.id = `trigger-${id}`;
        
        content.id = `content-${id}`;
        content.setAttribute('aria-labelledby', `trigger-${id}`);
        content.setAttribute('role', 'region');
      }
    });
  }

  #setupListeners(): void {
    this.#items.forEach(item => {
      const trigger = item.querySelector('[data-accordion-trigger]');
      trigger?.addEventListener('click', () => {
        const itemId = item.dataset.accordionItem;
        if (itemId) this.toggle(itemId);
      });
    });
  }

  toggle(itemId?: string): this {
    if (!itemId) {
      const firstItem = this.#items[0]?.dataset.accordionItem;
      if (firstItem) this.toggle(firstItem);
      return this;
    }

    const item = this.#el.querySelector(`[data-accordion-item="${itemId}"]`);
    const trigger = item?.querySelector('[data-accordion-trigger]');
    const content = item?.querySelector<HTMLElement>('[data-accordion-content]');
    
    if (!trigger || !content) return this;

    const isOpen = trigger.getAttribute('aria-expanded') === 'true';
    
    if (!this.#allowMultiple && !isOpen) {
      this.closeAll();
    }

    trigger.setAttribute('aria-expanded', String(!isOpen));
    content.classList.toggle('hidden', isOpen);
    
    emit(this.#el, 'accordion:toggled', { item: itemId, open: !isOpen });
    return this;
  }

  open(itemId: string): this {
    const item = this.#el.querySelector(`[data-accordion-item="${itemId}"]`);
    const trigger = item?.querySelector('[data-accordion-trigger]');
    const content = item?.querySelector<HTMLElement>('[data-accordion-content]');
    
    if (trigger && content) {
      if (!this.#allowMultiple) {
        this.closeAll();
      }
      trigger.setAttribute('aria-expanded', 'true');
      content.classList.remove('hidden');
      emit(this.#el, 'accordion:opened', { item: itemId });
    }
    return this;
  }

  close(itemId: string): this {
    const item = this.#el.querySelector(`[data-accordion-item="${itemId}"]`);
    const trigger = item?.querySelector('[data-accordion-trigger]');
    const content = item?.querySelector<HTMLElement>('[data-accordion-content]');
    
    if (trigger && content) {
      trigger.setAttribute('aria-expanded', 'false');
      content.classList.add('hidden');
      emit(this.#el, 'accordion:closed', { item: itemId });
    }
    return this;
  }

  openAll(): this {
    this.#items.forEach(item => {
      const itemId = item.dataset.accordionItem;
      if (itemId) this.open(itemId);
    });
    return this;
  }

  closeAll(): this {
    this.#items.forEach(item => {
      const trigger = item.querySelector('[data-accordion-trigger]');
      const content = item.querySelector<HTMLElement>('[data-accordion-content]');
      if (trigger && content) {
        trigger.setAttribute('aria-expanded', 'false');
        content.classList.add('hidden');
      }
    });
    return this;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
