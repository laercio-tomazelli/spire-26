import { KeyboardShortcut, KeyboardShortcutsManager } from '../types';

export class KeyboardShortcutsManagerClass implements KeyboardShortcutsManager {
  #shortcuts: KeyboardShortcut[] = [];
  #enabled = true;
  #boundHandler: (e: KeyboardEvent) => void;

  constructor() {
    this.#boundHandler = this.#handleKeydown.bind(this);
    document.addEventListener('keydown', this.#boundHandler);
  }

  #handleKeydown(e: KeyboardEvent): void {
    if (!this.#enabled) return;
    
    // Skip if user is typing in an input
    const target = e.target as HTMLElement;
    if (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA' || target.isContentEditable) {
      return;
    }

    for (const shortcut of this.#shortcuts) {
      const ctrlMatch = !!shortcut.ctrl === (e.ctrlKey || e.metaKey);
      const altMatch = !!shortcut.alt === e.altKey;
      const shiftMatch = !!shortcut.shift === e.shiftKey;
      const keyMatch = e.key.toLowerCase() === shortcut.key.toLowerCase();

      if (ctrlMatch && altMatch && shiftMatch && keyMatch) {
        e.preventDefault();
        shortcut.handler(e);
        return;
      }
    }
  }

  register(shortcutOrKey: KeyboardShortcut | string, handler?: () => void): void {
    let shortcut: KeyboardShortcut;
    
    if (typeof shortcutOrKey === 'string') {
      // Parse string format like 'ctrl+shift+k' or 'escape'
      const parts = shortcutOrKey.toLowerCase().split('+');
      const key = parts.pop() || '';
      shortcut = {
        key,
        ctrl: parts.includes('ctrl'),
        alt: parts.includes('alt'),
        shift: parts.includes('shift'),
        meta: parts.includes('meta') || parts.includes('cmd'),
        handler: handler || (() => {})
      };
    } else {
      shortcut = shortcutOrKey;
    }
    
    // Remove existing shortcut with same key combo
    this.unregister(shortcut.key, {
      ctrl: shortcut.ctrl,
      alt: shortcut.alt,
      shift: shortcut.shift,
      meta: shortcut.meta
    });
    this.#shortcuts.push(shortcut);
  }

  unregister(key: string, modifiers?: { ctrl?: boolean; alt?: boolean; shift?: boolean; meta?: boolean }): void {
    this.#shortcuts = this.#shortcuts.filter(s => {
      if (s.key.toLowerCase() !== key.toLowerCase()) return true;
      if (modifiers) {
        if (!!s.ctrl !== !!modifiers.ctrl) return true;
        if (!!s.alt !== !!modifiers.alt) return true;
        if (!!s.shift !== !!modifiers.shift) return true;
        if (!!s.meta !== !!modifiers.meta) return true;
      }
      return false;
    });
  }

  list(): KeyboardShortcut[] {
    return [...this.#shortcuts];
  }

  enable(): void {
    this.#enabled = true;
  }

  disable(): void {
    this.#enabled = false;
  }
}

export const shortcuts = new KeyboardShortcutsManagerClass();
