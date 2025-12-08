/**
 * DOM Utilities
 *
 * Vanilla JavaScript utilities for common DOM operations.
 * Replaces Alpine.js x-show, x-cloak, dropdowns, transitions, etc.
 */

import { events } from './EventBus';

// =====================
// ELEMENT VISIBILITY
// =====================

/**
 * Show an element (removes hidden class and sets display)
 */
export function show(el: HTMLElement | null, display: string = 'block'): void {
  if (!el) return;
  el.classList.remove('hidden');
  el.style.display = display;
  el.removeAttribute('data-hidden');
}

/**
 * Hide an element
 */
export function hide(el: HTMLElement | null): void {
  if (!el) return;
  el.classList.add('hidden');
  el.style.display = 'none';
  el.setAttribute('data-hidden', 'true');
}

/**
 * Toggle element visibility
 */
export function toggle(el: HTMLElement | null, display: string = 'block'): boolean {
  if (!el) return false;
  const isHidden = el.classList.contains('hidden') || el.style.display === 'none';
  if (isHidden) {
    show(el, display);
    return true;
  } else {
    hide(el);
    return false;
  }
}

/**
 * Check if element is visible
 */
export function isVisible(el: HTMLElement | null): boolean {
  if (!el) return false;
  return !el.classList.contains('hidden') && el.style.display !== 'none';
}

// =====================
// TRANSITIONS
// =====================

interface TransitionOptions {
  enter?: string;
  enterStart?: string;
  enterEnd?: string;
  leave?: string;
  leaveStart?: string;
  leaveEnd?: string;
  duration?: number;
}

const defaultTransition: TransitionOptions = {
  enter: 'transition ease-out duration-100',
  enterStart: 'opacity-0 scale-95',
  enterEnd: 'opacity-100 scale-100',
  leave: 'transition ease-in duration-75',
  leaveStart: 'opacity-100 scale-100',
  leaveEnd: 'opacity-0 scale-95',
  duration: 100,
};

/**
 * Show element with transition
 */
export function showWithTransition(
  el: HTMLElement | null,
  options: TransitionOptions = {}
): Promise<void> {
  return new Promise((resolve) => {
    if (!el) {
      resolve();
      return;
    }

    const opts = { ...defaultTransition, ...options };

    // Setup initial state
    el.classList.remove('hidden');
    el.style.display = '';

    // Add enter classes
    if (opts.enter) el.className += ` ${opts.enter}`;
    if (opts.enterStart) el.className += ` ${opts.enterStart}`;

    // Force reflow
    el.offsetHeight;

    // Remove start, add end
    if (opts.enterStart) {
      opts.enterStart.split(' ').forEach((c) => el.classList.remove(c));
    }
    if (opts.enterEnd) {
      opts.enterEnd.split(' ').forEach((c) => el.classList.add(c));
    }

    // Cleanup after transition
    setTimeout(() => {
      if (opts.enter) {
        opts.enter.split(' ').forEach((c) => el.classList.remove(c));
      }
      if (opts.enterEnd) {
        opts.enterEnd.split(' ').forEach((c) => el.classList.remove(c));
      }
      resolve();
    }, opts.duration);
  });
}

/**
 * Hide element with transition
 */
export function hideWithTransition(
  el: HTMLElement | null,
  options: TransitionOptions = {}
): Promise<void> {
  return new Promise((resolve) => {
    if (!el) {
      resolve();
      return;
    }

    const opts = { ...defaultTransition, ...options };

    // Add leave classes
    if (opts.leave) el.className += ` ${opts.leave}`;
    if (opts.leaveStart) el.className += ` ${opts.leaveStart}`;

    // Force reflow
    el.offsetHeight;

    // Remove start, add end
    if (opts.leaveStart) {
      opts.leaveStart.split(' ').forEach((c) => el.classList.remove(c));
    }
    if (opts.leaveEnd) {
      opts.leaveEnd.split(' ').forEach((c) => el.classList.add(c));
    }

    // Hide after transition
    setTimeout(() => {
      el.classList.add('hidden');
      el.style.display = 'none';

      // Cleanup classes
      if (opts.leave) {
        opts.leave.split(' ').forEach((c) => el.classList.remove(c));
      }
      if (opts.leaveEnd) {
        opts.leaveEnd.split(' ').forEach((c) => el.classList.remove(c));
      }
      resolve();
    }, opts.duration);
  });
}

// =====================
// DROPDOWNS
// =====================

interface DropdownOptions {
  trigger: HTMLElement;
  content: HTMLElement;
  closeOnClickOutside?: boolean;
  closeOnEscape?: boolean;
  transition?: TransitionOptions;
}

interface DropdownInstance {
  open: () => void;
  close: () => void;
  toggle: () => void;
  isOpen: () => boolean;
  destroy: () => void;
}

/**
 * Create a dropdown behavior
 */
export function createDropdown(options: DropdownOptions): DropdownInstance {
  const { trigger, content, closeOnClickOutside = true, closeOnEscape = true, transition } = options;

  let isOpen = false;

  const open = async () => {
    if (isOpen) return;
    isOpen = true;
    if (transition) {
      await showWithTransition(content, transition);
    } else {
      show(content);
    }
    events.emit('dropdown:open', { trigger, content });
  };

  const close = async () => {
    if (!isOpen) return;
    isOpen = false;
    if (transition) {
      await hideWithTransition(content, transition);
    } else {
      hide(content);
    }
    events.emit('dropdown:close', { trigger, content });
  };

  const toggleDropdown = () => {
    if (isOpen) {
      close();
    } else {
      open();
    }
  };

  // Click handler for trigger
  const handleTriggerClick = (e: Event) => {
    e.stopPropagation();
    toggleDropdown();
  };

  // Click outside handler
  const handleClickOutside = (e: Event) => {
    if (!isOpen) return;
    const target = e.target as Node;
    if (!trigger.contains(target) && !content.contains(target)) {
      close();
    }
  };

  // Escape key handler
  const handleEscape = (e: KeyboardEvent) => {
    if (!isOpen) return;
    if (e.key === 'Escape') {
      close();
      trigger.focus();
    }
  };

  // Setup listeners
  trigger.addEventListener('click', handleTriggerClick);

  if (closeOnClickOutside) {
    document.addEventListener('click', handleClickOutside);
  }

  if (closeOnEscape) {
    document.addEventListener('keydown', handleEscape);
  }

  // Initial state - hidden
  hide(content);

  return {
    open,
    close,
    toggle: toggleDropdown,
    isOpen: () => isOpen,
    destroy: () => {
      trigger.removeEventListener('click', handleTriggerClick);
      document.removeEventListener('click', handleClickOutside);
      document.removeEventListener('keydown', handleEscape);
    },
  };
}

// =====================
// CLICK OUTSIDE
// =====================

/**
 * Execute callback when clicking outside an element
 */
export function onClickOutside(
  el: HTMLElement,
  callback: () => void
): () => void {
  const handler = (e: Event) => {
    const target = e.target as Node;
    if (!el.contains(target)) {
      callback();
    }
  };

  document.addEventListener('click', handler);

  // Return cleanup function
  return () => document.removeEventListener('click', handler);
}

// =====================
// DEBOUNCE FOR INPUTS
// =====================

/**
 * Debounce an input handler
 */
export function debounceInput(
  input: HTMLInputElement,
  callback: (value: string) => void,
  delay: number = 300
): () => void {
  let timeout: ReturnType<typeof setTimeout>;

  const handler = () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      callback(input.value);
    }, delay);
  };

  input.addEventListener('input', handler);

  return () => {
    clearTimeout(timeout);
    input.removeEventListener('input', handler);
  };
}

// =====================
// DATA BINDING HELPERS
// =====================

/**
 * Two-way bind an input to a callback
 */
export function bindInput(
  input: HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement,
  onChange: (value: string) => void
): () => void {
  const handler = () => onChange(input.value);
  input.addEventListener('input', handler);
  input.addEventListener('change', handler);

  return () => {
    input.removeEventListener('input', handler);
    input.removeEventListener('change', handler);
  };
}

// =====================
// CONDITIONAL DISPLAY
// =====================

/**
 * Show/hide element based on condition
 */
export function showIf(el: HTMLElement | null, condition: boolean, display: string = 'block'): void {
  if (condition) {
    show(el, display);
  } else {
    hide(el);
  }
}

// =====================
// QUERY HELPERS
// =====================

/**
 * Query selector with type safety
 */
export function $(selector: string, parent: Element | Document = document): HTMLElement | null {
  return parent.querySelector(selector);
}

/**
 * Query selector all with type safety
 */
export function $$(selector: string, parent: Element | Document = document): HTMLElement[] {
  return Array.from(parent.querySelectorAll(selector));
}

// =====================
// EXPORT DOM UTILITIES
// =====================

export const dom = {
  show,
  hide,
  toggle,
  isVisible,
  showWithTransition,
  hideWithTransition,
  createDropdown,
  onClickOutside,
  debounceInput,
  bindInput,
  showIf,
  $,
  $$,
};
