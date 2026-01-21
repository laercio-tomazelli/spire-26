import type { CommandPaletteInstance, CommandItem } from '../types';
import { instances, emit } from '../core';

export class CommandPalette implements CommandPaletteInstance {
  #el: HTMLElement | null = null;
  #commands: CommandItem[] = [];
  #filteredCommands: CommandItem[] = [];
  #selectedIndex = 0;
  #isOpen = false;
  #boundHandleKeydown: (e: KeyboardEvent) => void;

  constructor(el?: HTMLElement) {
    this.#boundHandleKeydown = this.#handleKeydown.bind(this);
    this.#setupGlobalShortcut();
    
    if (el) {
      this.#el = el;
      this.#parseCommands();
    }
  }

  #setupGlobalShortcut(): void {
    document.addEventListener('keydown', (e) => {
      if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
        e.preventDefault();
        this.toggle();
      }
    });
  }

  #parseCommands(): void {
    this.#el?.querySelectorAll('[data-command]').forEach(cmd => {
      const el = cmd as HTMLElement;
      this.#commands.push({
        id: el.dataset.command || '',
        title: el.dataset.title || el.textContent?.trim() || '',
        description: el.dataset.description,
        icon: el.dataset.icon,
        shortcut: el.dataset.shortcut,
        category: el.dataset.category,
        handler: () => {
          const handlerName = el.dataset.handler;
          if (handlerName && typeof (window as unknown as Record<string, unknown>)[handlerName] === 'function') {
            ((window as unknown as Record<string, unknown>)[handlerName] as () => void)();
          }
          emit(document.body, 'command:executed', { command: el.dataset.command });
        }
      });
    });
    this.#filteredCommands = [...this.#commands];
  }

  #createPalette(): HTMLElement {
    const palette = document.createElement('div');
    palette.className = 'fixed inset-0 z-50 flex items-start justify-center pt-[20vh] bg-black/50 backdrop-blur-sm';
    palette.innerHTML = `
      <div class="w-full max-w-xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <input type="text" placeholder="Digite um comando..." 
            class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-xl border-0 focus:ring-2 focus:ring-blue-500 dark:text-white"
            data-command-search>
        </div>
        <div class="max-h-80 overflow-y-auto p-2" data-command-list></div>
        <div class="p-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between text-xs text-gray-500">
          <span>↑↓ navegar</span>
          <span>↵ executar</span>
          <span>esc fechar</span>
        </div>
      </div>
    `;
    return palette;
  }

  #renderCommands(): void {
    const list = this.#el?.querySelector('[data-command-list]');
    if (!list) return;

    // Group by category
    const grouped = this.#filteredCommands.reduce((acc, cmd) => {
      const cat = cmd.category || 'Geral';
      if (!acc[cat]) acc[cat] = [];
      acc[cat].push(cmd);
      return acc;
    }, {} as Record<string, CommandItem[]>);

    let html = '';
    let globalIndex = 0;

    for (const [category, commands] of Object.entries(grouped)) {
      html += `<div class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 py-2">${category}</div>`;
      
      for (const cmd of commands) {
        const isSelected = globalIndex === this.#selectedIndex;
        html += `
          <button type="button" data-command-item="${cmd.id}" 
            class="w-full flex items-center gap-3 px-3 py-2 rounded-xl transition-colors ${isSelected ? 'bg-blue-500 text-white' : 'hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-300'}">
            ${cmd.icon ? `<span class="text-lg">${cmd.icon}</span>` : '<span class="w-5"></span>'}
            <div class="flex-1 text-left">
              <div class="font-medium">${cmd.title}</div>
              ${cmd.description ? `<div class="text-sm opacity-70">${cmd.description}</div>` : ''}
            </div>
            ${cmd.shortcut ? `<kbd class="px-2 py-1 text-xs ${isSelected ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-600'} rounded">${cmd.shortcut}</kbd>` : ''}
          </button>
        `;
        globalIndex++;
      }
    }

    list.innerHTML = html;

    // Attach click listeners
    list.querySelectorAll('[data-command-item]').forEach((btn, index) => {
      btn.addEventListener('click', () => {
        this.#executeCommand(index);
      });
    });
  }

  #handleKeydown(e: KeyboardEvent): void {
    if (e.key === 'Escape') {
      this.close();
      return;
    }

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      this.#selectedIndex = Math.min(this.#selectedIndex + 1, this.#filteredCommands.length - 1);
      this.#renderCommands();
      return;
    }

    if (e.key === 'ArrowUp') {
      e.preventDefault();
      this.#selectedIndex = Math.max(this.#selectedIndex - 1, 0);
      this.#renderCommands();
      return;
    }

    if (e.key === 'Enter') {
      e.preventDefault();
      this.#executeCommand(this.#selectedIndex);
      return;
    }
  }

  #executeCommand(index: number): void {
    const cmd = this.#filteredCommands[index];
    if (cmd) {
      this.close();
      cmd.handler();
    }
  }

  #filterCommands(query: string): void {
    const q = query.toLowerCase();
    this.#filteredCommands = this.#commands.filter(cmd => 
      cmd.title.toLowerCase().includes(q) ||
      cmd.description?.toLowerCase().includes(q) ||
      cmd.category?.toLowerCase().includes(q)
    );
    this.#selectedIndex = 0;
    this.#renderCommands();
  }

  open(): this {
    if (this.#isOpen) return this;

    this.#isOpen = true;
    this.#el = this.#createPalette();
    document.body.appendChild(this.#el);
    
    this.#renderCommands();

    // Focus search input
    const searchInput = this.#el.querySelector('[data-command-search]') as HTMLInputElement;
    searchInput?.focus();

    // Search filter
    searchInput?.addEventListener('input', () => {
      this.#filterCommands(searchInput.value);
    });

    // Keyboard navigation
    document.addEventListener('keydown', this.#boundHandleKeydown);

    // Close on backdrop click
    this.#el.addEventListener('click', (e) => {
      if (e.target === this.#el) this.close();
    });

    emit(document.body, 'commandpalette:opened', {});
    return this;
  }

  close(): this {
    if (!this.#isOpen) return this;

    this.#isOpen = false;
    this.#el?.remove();
    this.#el = null;
    this.#filteredCommands = [...this.#commands];
    this.#selectedIndex = 0;

    document.removeEventListener('keydown', this.#boundHandleKeydown);
    emit(document.body, 'commandpalette:closed', {});
    return this;
  }

  toggle(): this {
    return this.#isOpen ? this.close() : this.open();
  }

  setCommands(commands: CommandItem[]): this {
    this.#commands = commands;
    this.#filteredCommands = [...commands];
    if (this.#isOpen) this.#renderCommands();
    return this;
  }

  registerCommand(command: CommandItem): this {
    this.#commands.push(command);
    this.#filteredCommands = [...this.#commands];
    if (this.#isOpen) this.#renderCommands();
    return this;
  }

  // Alias for registerCommand
  register(command: CommandItem): this {
    return this.registerCommand(command);
  }

  destroy(): void {
    this.close();
    if (this.#el) instances.delete(this.#el);
  }
}
