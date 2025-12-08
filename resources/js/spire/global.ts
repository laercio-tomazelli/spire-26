/**
 * Spire UI - Global API
 *
 * This file creates the global SpireUI API and exposes it on window.
 * It provides the init() function and all utilities.
 */

// Alpine.js - for declarative UI components
import Alpine from 'alpinejs';
(window as unknown as { Alpine: typeof Alpine }).Alpine = Alpine;
Alpine.start();

import { SpireUIAPI, SpireUIInstance } from './types';
import { instances, setGlobalErrorHandler } from './core';

// Components
import { Accordion } from './components/Accordion';
import { Button } from './components/Button';
import { Carousel } from './components/Carousel';
import { Clipboard } from './components/Clipboard';
import { Collapse } from './components/Collapse';
import { ColorPicker } from './components/ColorPicker';
import { CommandPalette } from './components/CommandPalette';
import { ContextMenu } from './components/ContextMenu';
import { DatePicker } from './components/DatePicker';
import { DateRangePicker } from './components/DateRangePicker';
import { Drawer } from './components/Drawer';
import { Dropdown } from './components/Dropdown';
import { FileUpload } from './components/FileUpload';
import { FormValidator } from './components/FormValidator';
import { InfiniteScroll } from './components/InfiniteScroll';
import { Input } from './components/Input';
import { LazyLoad } from './components/LazyLoad';
import { Modal } from './components/Modal';
import { MultiSelect } from './components/MultiSelect';
import { Persist } from './components/Persist';
import { Popover } from './components/Popover';
import { Progress } from './components/Progress';
import { RangeSlider } from './components/RangeSlider';
import { Rating } from './components/Rating';
import { Select } from './components/Select';
import { Skeleton } from './components/Skeleton';
import { Stepper } from './components/Stepper';
import { Table } from './components/Table';
import { Sidebar } from './components/Sidebar';
import { Navbar } from './components/Navbar';
import { Tabs } from './components/Tabs';
import { Tooltip } from './components/Tooltip';
import { VirtualScroll } from './components/VirtualScroll';

// Utilities
import { toast } from './utilities/Toast';
import { confirm } from './utilities/Confirm';
import { shortcuts } from './utilities/Shortcuts';
import { events } from './utilities/EventBus';
import { http } from './utilities/Http';
import { currency } from './utilities/Currency';
import { mask } from './utilities/Mask';
import { perf } from './utilities/Perf';
import { a11y } from './utilities/A11y';
import { debounce, throttle } from './utilities/timing';

// Component registry for data-v initialization
const Components: Record<string, new (el: HTMLElement) => SpireUIInstance> = {
  button: Button as unknown as new (el: HTMLElement) => SpireUIInstance,
  input: Input as unknown as new (el: HTMLElement) => SpireUIInstance,
  modal: Modal,
  dropdown: Dropdown,
  table: Table as unknown as new (el: HTMLElement) => SpireUIInstance,
  tabs: Tabs,
  collapse: Collapse,
  rating: Rating,
  accordion: Accordion,
  tooltip: Tooltip,
  select: Select,
  multiselect: MultiSelect,
  drawer: Drawer,
  popover: Popover,
  progress: Progress as unknown as new (el: HTMLElement) => SpireUIInstance,
  skeleton: Skeleton,
  clipboard: Clipboard,
  stepper: Stepper,
  form: FormValidator as unknown as new (el: HTMLElement) => SpireUIInstance,
  lazy: LazyLoad,
  infinitescroll: InfiniteScroll,
  datepicker: DatePicker as unknown as new (el: HTMLElement) => SpireUIInstance,
  daterangepicker: DateRangePicker as unknown as new (el: HTMLElement) => SpireUIInstance,
  colorpicker: ColorPicker as unknown as new (el: HTMLElement) => SpireUIInstance,
  range: RangeSlider,
  upload: FileUpload,
  virtualscroll: VirtualScroll,
  persist: Persist,
  contextmenu: ContextMenu,
  carousel: Carousel,
  sidebar: Sidebar,
  navbar: Navbar
};

// Command palette singleton
let commandPalette: CommandPalette | null = null;

/**
 * Get or create the command palette instance
 */
function getCommandPalette(): CommandPalette {
  if (!commandPalette) {
    // Create container if it doesn't exist
    let container = document.querySelector<HTMLElement>('[data-v="commandpalette"]');
    if (!container) {
      container = document.createElement('div');
      container.setAttribute('data-v', 'commandpalette');
      document.body.appendChild(container);
    }
    commandPalette = new CommandPalette(container);
  }
  return commandPalette;
}

/**
 * The main SpireUI API object
 */
export const SpireUI: SpireUIAPI = {
  init(): void {
    document.querySelectorAll<HTMLElement>('[data-v]').forEach(el => {
      const type = el.dataset.v;
      if (type && Components[type] && !instances.has(el)) {
        const ComponentClass = Components[type];
        const instance = new ComponentClass(el);
        instances.set(el, instance);

        // Maintain backwards compatibility with $component API
        Object.defineProperty(el, `$${type}`, {
          get: () => instances.get(el),
          configurable: true
        });
      }
    });

    // Auto-initialize carousels with data-carousel attribute
    document.querySelectorAll<HTMLElement>('[data-carousel]').forEach(el => {
      if (!instances.has(el)) {
        const instance = new Carousel(el);
        instances.set(el, instance);
      }
    });
  },

  get<T extends SpireUIInstance>(el: HTMLElement): T | undefined {
    return instances.get(el) as T | undefined;
  },

  destroy(el: HTMLElement): void {
    const instance = instances.get(el);
    if (instance?.destroy) {
      instance.destroy();
    }
  },

  destroyAll(): void {
    document.querySelectorAll<HTMLElement>('[data-v]').forEach(el => {
      this.destroy(el);
    });
  },

  // Helper to access collapse by ID
  collapse(id: string) {
    const el = document.querySelector<HTMLElement>(`[data-collapse="${id}"]`);
    if (!el) return null;

    if (!instances.has(el)) {
      const instance = new Collapse(el);
      instances.set(el, instance);
    }

    return instances.get(el) as import('./types').CollapseInstance;
  },

  // Helper to access carousel by ID
  carousel(id: string) {
    const el = document.querySelector<HTMLElement>(`[data-carousel="${id}"]`);
    if (!el) return null;

    if (!instances.has(el)) {
      const instance = new Carousel(el);
      instances.set(el, instance);
    }

    return instances.get(el) as import('./types').CarouselInstance;
  },

  // Utilities
  toast,
  confirm,
  shortcuts,
  get command() {
    return getCommandPalette();
  },
  debounce,
  throttle,
  events,
  http,
  currency,
  mask,
  perf,
  a11y,
  onError: setGlobalErrorHandler
};

// =====================
// AUTO INITIALIZATION
// =====================
['DOMContentLoaded', 'livewire:navigated', 'turbo:render', 'astro:page-load'].forEach(ev =>
  document.addEventListener(ev, () => setTimeout(SpireUI.init, 10))
);

// =====================
// THEME TOGGLE
// =====================
document.addEventListener('DOMContentLoaded', () => {
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const savedTheme = localStorage.getItem('theme');

  if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }

  if (!document.getElementById('theme-toggle')) {
    const btn = document.createElement('button');
    btn.id = 'theme-toggle';
    btn.className = 'fixed bottom-6 right-6 z-50 p-3 bg-white dark:bg-gray-800 rounded-full shadow-lg border border-gray-200 dark:border-gray-700 hover:scale-110 transition-transform';
    btn.setAttribute('aria-label', 'Alternar tema claro/escuro');
    btn.innerHTML = `
      <svg class="w-6 h-6 hidden dark:block text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
      </svg>
      <svg class="w-6 h-6 block dark:hidden text-gray-700" fill="currentColor" viewBox="0 0 20 20">
        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
      </svg>
    `;
    document.body.appendChild(btn);
  }

  document.getElementById('theme-toggle')?.addEventListener('click', () => {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
  });

  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('theme')) {
      document.documentElement.classList.toggle('dark', e.matches);
    }
  });
});

// Expose globally
(window as unknown as { $v: typeof SpireUI; SpireUI: typeof SpireUI; Spire: typeof SpireUI }).$v = SpireUI;
(window as unknown as { SpireUI: typeof SpireUI }).SpireUI = SpireUI;
(window as unknown as { Spire: typeof SpireUI }).Spire = SpireUI;

export default SpireUI;
