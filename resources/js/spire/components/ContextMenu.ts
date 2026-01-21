import type { ContextMenuInstance, ContextMenuItem } from '../types';
import { instances, emit } from '../core';

export class ContextMenu implements ContextMenuInstance {
  #el: HTMLElement | null = null;
  #items: ContextMenuItem[] = [];
  #isOpen = false;
  #targetElement: HTMLElement | null = null;
  #boundHandleClick: (e: MouseEvent) => void;
  #boundHandleKeydown: (e: KeyboardEvent) => void;
  #boundHandleContextMenu: (e: MouseEvent) => void;
  #selectedIndex = -1;

  constructor(el: HTMLElement) {
    this.#targetElement = el;
    this.#items = this.#parseItems(el);
    
    this.#boundHandleClick = this.#handleOutsideClick.bind(this);
    this.#boundHandleKeydown = this.#handleKeydown.bind(this);
    this.#boundHandleContextMenu = this.#handleContextMenu.bind(this);
    
    this.#setupListeners();
  }

  #parseItems(el: HTMLElement): ContextMenuItem[] {
    const itemsAttr = el.dataset.contextItems;
    if (itemsAttr) {
      try {
        return JSON.parse(itemsAttr);
      } catch {
        return [];
      }
    }
    return [];
  }

  #setupListeners(): void {
    this.#targetElement?.addEventListener('contextmenu', this.#boundHandleContextMenu);
  }

  #handleContextMenu(e: MouseEvent): void {
    e.preventDefault();
    this.show(e.clientX, e.clientY);
  }

  #handleOutsideClick(e: MouseEvent): void {
    if (this.#el && !this.#el.contains(e.target as Node)) {
      this.hide();
    }
  }

  #handleKeydown(e: KeyboardEvent): void {
    if (!this.#isOpen) return;

    const items = this.#getSelectableItems();
    
    switch (e.key) {
      case 'Escape':
        e.preventDefault();
        this.hide();
        break;
      case 'ArrowDown':
        e.preventDefault();
        this.#selectedIndex = (this.#selectedIndex + 1) % items.length;
        this.#updateSelection();
        break;
      case 'ArrowUp':
        e.preventDefault();
        this.#selectedIndex = (this.#selectedIndex - 1 + items.length) % items.length;
        this.#updateSelection();
        break;
      case 'Enter':
        e.preventDefault();
        if (this.#selectedIndex >= 0 && items[this.#selectedIndex]) {
          this.#executeItem(items[this.#selectedIndex]);
        }
        break;
    }
  }

  #getSelectableItems(): ContextMenuItem[] {
    return this.#items.filter(item => !item.divider && !item.disabled);
  }

  #updateSelection(): void {
    if (!this.#el) return;
    
    const buttons = this.#el.querySelectorAll('[data-context-item]');
    buttons.forEach((btn, i) => {
      btn.classList.toggle('bg-blue-100', i === this.#selectedIndex);
      btn.classList.toggle('dark:bg-blue-900/30', i === this.#selectedIndex);
    });
  }

  #executeItem(item: ContextMenuItem): void {
    if (item.disabled) return;
    
    this.hide();
    
    if (item.handler) {
      item.handler();
    }
    
    emit(this.#targetElement!, 'contextmenu:select', { item });
  }

  #createMenu(): HTMLElement {
    const menu = document.createElement('div');
    menu.className = 'fixed z-[9999] min-w-48 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 py-1 overflow-hidden animate-in fade-in zoom-in-95 duration-150';
    menu.setAttribute('role', 'menu');
    menu.innerHTML = this.#renderItems(this.#items);
    return menu;
  }

  #renderItems(items: ContextMenuItem[]): string {
    return items.map((item, index) => {
      if (item.divider) {
        return '<div class="my-1 border-t border-gray-200 dark:border-gray-700"></div>';
      }

      const disabledClass = item.disabled ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer';
      const dangerClass = item.danger ? 'text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20' : 'text-gray-700 dark:text-gray-300';

      return `
        <button
          type="button"
          data-context-item="${index}"
          data-item-id="${item.id}"
          class="w-full px-3 py-2 text-sm flex items-center gap-3 ${disabledClass} ${dangerClass} transition-colors"
          role="menuitem"
          ${item.disabled ? 'disabled' : ''}
        >
          ${item.icon ? `<span class="w-5 text-center">${item.icon}</span>` : '<span class="w-5"></span>'}
          <span class="flex-1 text-left">${item.label}</span>
          ${item.shortcut ? `<span class="text-xs text-gray-400 dark:text-gray-500 ml-4">${item.shortcut}</span>` : ''}
        </button>
      `;
    }).join('');
  }

  #positionMenu(x: number, y: number): void {
    if (!this.#el) return;

    const rect = this.#el.getBoundingClientRect();
    const viewportWidth = window.innerWidth;
    const viewportHeight = window.innerHeight;

    // Adjust if menu would go off screen
    let finalX = x;
    let finalY = y;

    if (x + rect.width > viewportWidth) {
      finalX = viewportWidth - rect.width - 8;
    }
    if (y + rect.height > viewportHeight) {
      finalY = viewportHeight - rect.height - 8;
    }

    this.#el.style.left = `${Math.max(8, finalX)}px`;
    this.#el.style.top = `${Math.max(8, finalY)}px`;
  }

  #attachMenuListeners(): void {
    if (!this.#el) return;

    this.#el.querySelectorAll('[data-context-item]').forEach(btn => {
      btn.addEventListener('click', () => {
        const index = parseInt((btn as HTMLElement).dataset.contextItem || '0');
        const selectableItems = this.#getSelectableItems();
        // Map button index to item
        let selectableIndex = 0;
        for (const item of this.#items) {
          if (!item.divider && !item.disabled) {
            if (selectableIndex === index) {
              this.#executeItem(item);
              return;
            }
            selectableIndex++;
          }
        }
        // Fallback - use direct index
        if (this.#items[index]) {
          this.#executeItem(this.#items[index]);
        }
      });

      btn.addEventListener('mouseenter', () => {
        const index = parseInt((btn as HTMLElement).dataset.contextItem || '0');
        this.#selectedIndex = index;
        this.#updateSelection();
      });
    });
  }

  show(x: number, y: number, items?: ContextMenuItem[]): this {
    // Hide any existing menu
    this.hide();

    if (items) {
      this.#items = items;
    }

    if (this.#items.length === 0) return this;

    this.#isOpen = true;
    this.#selectedIndex = -1;
    this.#el = this.#createMenu();
    document.body.appendChild(this.#el);

    // Position after render to get correct dimensions
    requestAnimationFrame(() => {
      this.#positionMenu(x, y);
      this.#attachMenuListeners();
    });

    // Add global listeners
    setTimeout(() => {
      document.addEventListener('click', this.#boundHandleClick);
      document.addEventListener('keydown', this.#boundHandleKeydown);
      document.addEventListener('contextmenu', this.#boundHandleClick);
    }, 0);

    emit(this.#targetElement!, 'contextmenu:opened', {});
    return this;
  }

  hide(): this {
    if (!this.#isOpen) return this;

    this.#isOpen = false;
    this.#el?.remove();
    this.#el = null;

    document.removeEventListener('click', this.#boundHandleClick);
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    document.removeEventListener('contextmenu', this.#boundHandleClick);

    emit(this.#targetElement!, 'contextmenu:closed', {});
    return this;
  }

  setItems(items: ContextMenuItem[]): this {
    this.#items = items;
    return this;
  }

  isOpen(): boolean {
    return this.#isOpen;
  }

  destroy(): void {
    this.hide();
    this.#targetElement?.removeEventListener('contextmenu', this.#boundHandleContextMenu);
    instances.delete(this.#targetElement!);
  }
}
