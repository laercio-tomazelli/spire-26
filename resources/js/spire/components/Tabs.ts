import type { TabsInstance } from '../types';
import { instances, emit } from '../core';

export class Tabs implements TabsInstance {
  #el: HTMLElement;
  #tablist: HTMLElement | null = null;
  #panelsContainer: HTMLElement | null = null;
  #currentTab: string | null = null;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#tablist = el.querySelector('[role="tablist"]') || el.querySelector('[data-tablist]');
    this.#panelsContainer = el.querySelector('[data-panels]') || el;
    this.#setupA11y();
    this.#setupListeners();
    this.#initializeActiveTab();
  }

  #getTabs(): HTMLElement[] {
    if (this.#tablist) {
      return Array.from(this.#tablist.querySelectorAll(':scope > [data-tab]'));
    }
    const tablist = this.#el.querySelector('[role="tablist"]');
    if (tablist) {
      return Array.from(tablist.querySelectorAll(':scope > [data-tab]'));
    }
    return [];
  }

  #getPanels(): HTMLElement[] {
    const panelsContainer = this.#panelsContainer;
    if (panelsContainer && panelsContainer !== this.#el) {
      return Array.from(panelsContainer.querySelectorAll<HTMLElement>(':scope > [data-panel]'));
    }
    const tabPanelsDiv = this.#el.querySelector('.tab-panels');
    if (tabPanelsDiv) {
      return Array.from(tabPanelsDiv.querySelectorAll<HTMLElement>(':scope > [data-panel]'));
    }
    return Array.from(this.#el.querySelectorAll<HTMLElement>('[data-panel]')).filter(panel => {
      const closestTabs = panel.closest('[data-v="tabs"]');
      return closestTabs === this.#el;
    });
  }

  #setupA11y(): void {
    if (this.#tablist) {
      this.#tablist.setAttribute('role', 'tablist');
    }
    this.#getTabs().forEach((tab, index) => {
      this.#setupTabA11y(tab, index);
    });
  }

  #setupTabA11y(tab: HTMLElement, index: number): void {
    const tabId = tab.dataset.tab || `tab-${index}`;
    const panelId = `panel-${tabId}`;
    
    tab.setAttribute('role', 'tab');
    tab.setAttribute('aria-controls', panelId);
    tab.id = `tab-${tabId}`;
    
    const panel = this.#el.querySelector(`[data-panel="${tabId}"]`);
    if (panel) {
      panel.setAttribute('role', 'tabpanel');
      panel.setAttribute('aria-labelledby', `tab-${tabId}`);
      panel.id = panelId;
    }
  }

  #setupListeners(): void {
    this.#el.addEventListener('click', (e) => {
      const tab = (e.target as HTMLElement).closest('[data-tab]') as HTMLElement;
      if (!tab || tab.hasAttribute('disabled') || tab.classList.contains('disabled')) return;
      
      const tabsContainer = tab.closest('[data-v="tabs"]');
      if (tabsContainer !== this.#el) return;
      
      const tabId = tab.dataset.tab;
      if (tabId) this.show(tabId);
    });

    this.#el.addEventListener('keydown', (e: KeyboardEvent) => {
      const tab = (e.target as HTMLElement).closest('[data-tab]') as HTMLElement;
      if (!tab) return;

      const tabs = this.#getTabs().filter(t => !t.hasAttribute('disabled') && !t.classList.contains('disabled') && !t.classList.contains('tab-hidden'));
      const currentIndex = tabs.indexOf(tab);
      let newIndex = currentIndex;

      if (e.key === 'ArrowRight') {
        newIndex = (currentIndex + 1) % tabs.length;
      } else if (e.key === 'ArrowLeft') {
        newIndex = (currentIndex - 1 + tabs.length) % tabs.length;
      } else if (e.key === 'Home') {
        newIndex = 0;
      } else if (e.key === 'End') {
        newIndex = tabs.length - 1;
      } else {
        return;
      }

      e.preventDefault();
      const newTab = tabs[newIndex];
      const tabId = newTab?.dataset.tab;
      if (tabId) {
        this.show(tabId);
        newTab.focus();
      }
    });
  }

  #initializeActiveTab(): void {
    const activeTab = this.#el.querySelector<HTMLElement>('[data-tab].active, [data-tab][aria-selected="true"]');
    const tabs = this.#getTabs();
    const tabId = activeTab?.dataset.tab || tabs[0]?.dataset.tab;
    if (tabId) this.show(tabId);
  }

  show(tabId: string): this {
    const targetTab = this.#el.querySelector<HTMLElement>(`[data-tab="${tabId}"]`);
    if (!targetTab || targetTab.hasAttribute('disabled') || targetTab.classList.contains('disabled')) {
      return this;
    }

    this.#getTabs().forEach(tab => {
      const isActive = tab.dataset.tab === tabId;
      tab.classList.toggle('active', isActive);
      tab.setAttribute('aria-selected', String(isActive));
      tab.setAttribute('tabindex', isActive ? '0' : '-1');
    });

    this.#getPanels().forEach(panel => {
      const isActive = panel.dataset.panel === tabId;
      panel.classList.toggle('hidden', !isActive);
      panel.setAttribute('aria-hidden', String(!isActive));
    });

    this.#currentTab = tabId;
    emit(this.#el, 'tabs:changed', { tab: tabId });
    return this;
  }

  current(): string | null {
    return this.#currentTab;
  }

  disable(tabId: string): this {
    const tab = this.#el.querySelector<HTMLElement>(`[data-tab="${tabId}"]`);
    if (tab) {
      tab.setAttribute('disabled', '');
      tab.classList.add('disabled', 'opacity-50', 'cursor-not-allowed');
      tab.setAttribute('aria-disabled', 'true');
      
      if (this.#currentTab === tabId) {
        const availableTab = this.#getTabs().find(t => 
          !t.hasAttribute('disabled') && !t.classList.contains('tab-hidden')
        );
        if (availableTab?.dataset.tab) {
          this.show(availableTab.dataset.tab);
        }
      }
    }
    emit(this.#el, 'tabs:disabled', { tab: tabId });
    return this;
  }

  enable(tabId: string): this {
    const tab = this.#el.querySelector<HTMLElement>(`[data-tab="${tabId}"]`);
    if (tab) {
      tab.removeAttribute('disabled');
      tab.classList.remove('disabled', 'opacity-50', 'cursor-not-allowed');
      tab.setAttribute('aria-disabled', 'false');
    }
    emit(this.#el, 'tabs:enabled', { tab: tabId });
    return this;
  }

  hide(tabId: string): this {
    const tab = this.#el.querySelector<HTMLElement>(`[data-tab="${tabId}"]`);
    const panel = this.#el.querySelector<HTMLElement>(`[data-panel="${tabId}"]`);
    
    if (tab) {
      tab.classList.add('tab-hidden', 'hidden');
    }
    if (panel) {
      panel.classList.add('hidden');
    }
    
    if (this.#currentTab === tabId) {
      const availableTab = this.#getTabs().find(t => 
        !t.classList.contains('tab-hidden') && !t.hasAttribute('disabled')
      );
      if (availableTab?.dataset.tab) {
        this.show(availableTab.dataset.tab);
      }
    }
    
    emit(this.#el, 'tabs:hidden', { tab: tabId });
    return this;
  }

  unhide(tabId: string): this {
    const tab = this.#el.querySelector<HTMLElement>(`[data-tab="${tabId}"]`);
    
    if (tab) {
      tab.classList.remove('tab-hidden', 'hidden');
    }
    
    emit(this.#el, 'tabs:unhidden', { tab: tabId });
    return this;
  }

  add(config: { name: string; label: string; content: string; position?: number; active?: boolean }): this {
    const { name: tabId, label, content, position, active = false } = config;
    
    const tab = document.createElement('button');
    tab.dataset.tab = tabId;
    tab.className = 'px-4 py-3 text-sm font-medium border-b-2 -mb-px transition-colors border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 [&.active]:border-blue-600 [&.active]:text-blue-600 dark:[&.active]:border-blue-400 dark:[&.active]:text-blue-400';
    tab.textContent = label;
    
    const panel = document.createElement('div');
    panel.dataset.panel = tabId;
    panel.className = 'hidden';
    panel.innerHTML = content;
    
    const tablist = this.#tablist || this.#el.querySelector('[role="tablist"]');
    const panelsContainer = this.#panelsContainer;
    
    if (tablist) {
      const tabs = this.#getTabs();
      if (position !== undefined && position < tabs.length) {
        tabs[position].before(tab);
      } else {
        tablist.appendChild(tab);
      }
    }
    
    if (panelsContainer) {
      const panels = this.#getPanels();
      if (position !== undefined && position < panels.length) {
        panels[position].before(panel);
      } else {
        panelsContainer.appendChild(panel);
      }
    }
    
    this.#setupTabA11y(tab, this.#getTabs().length - 1);
    
    emit(this.#el, 'tabs:added', { tab: tabId, label });
    
    if (active) {
      this.show(tabId);
    }
    
    return this;
  }

  remove(tabId: string): this {
    const tab = this.#el.querySelector<HTMLElement>(`[data-tab="${tabId}"]`);
    const panel = this.#el.querySelector<HTMLElement>(`[data-panel="${tabId}"]`);
    
    if (this.#currentTab === tabId) {
      const tabs = this.#getTabs().filter(t => t.dataset.tab !== tabId);
      const nextTab = tabs.find(t => !t.hasAttribute('disabled') && !t.classList.contains('tab-hidden'));
      if (nextTab?.dataset.tab) {
        this.show(nextTab.dataset.tab);
      }
    }
    
    tab?.remove();
    panel?.remove();
    
    emit(this.#el, 'tabs:removed', { tab: tabId });
    return this;
  }

  list(): Array<{ name: string; label: string }> {
    return this.#getTabs()
      .filter(t => !t.classList.contains('tab-hidden'))
      .map(t => ({
        name: t.dataset.tab || '',
        label: t.textContent?.replace(/\d+$/, '').trim() || ''
      }))
      .filter(t => t.name);
  }

  highlight(tabId: string, options: { type?: 'error' | 'warning' | 'success' | 'info'; pulse?: boolean; badge?: string | number } = {}): this {
    const tab = this.#el.querySelector<HTMLElement>(`[data-tab="${tabId}"]`);
    if (!tab) return this;

    const { type = 'error', pulse = false, badge } = options;

    this.clearHighlight(tabId);

    const colorClasses: Record<string, string> = {
      error: 'text-red-600 dark:text-red-400',
      warning: 'text-amber-600 dark:text-amber-400',
      success: 'text-green-600 dark:text-green-400',
      info: 'text-blue-600 dark:text-blue-400'
    };

    const bgClasses: Record<string, string> = {
      error: 'bg-red-100 dark:bg-red-900/30',
      warning: 'bg-amber-100 dark:bg-amber-900/30',
      success: 'bg-green-100 dark:bg-green-900/30',
      info: 'bg-blue-100 dark:bg-blue-900/30'
    };

    tab.classList.add(...colorClasses[type].split(' '), ...bgClasses[type].split(' '), 'tab-highlighted');
    tab.dataset.highlightType = type;

    if (pulse) {
      tab.classList.add('animate-pulse');
      tab.dataset.highlightPulse = 'true';
    }

    if (badge !== undefined) {
      const existingBadge = tab.querySelector('[data-tab-badge]');
      existingBadge?.remove();

      const badgeEl = document.createElement('span');
      badgeEl.dataset.tabBadge = '';
      badgeEl.className = `ml-2 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold rounded-full ${
        type === 'error' ? 'bg-red-600 text-white' :
        type === 'warning' ? 'bg-amber-600 text-white' :
        type === 'success' ? 'bg-green-600 text-white' :
        'bg-blue-600 text-white'
      }`;
      badgeEl.textContent = String(badge);
      tab.appendChild(badgeEl);
    }

    emit(this.#el, 'tabs:highlighted', { tab: tabId, type, pulse, badge });
    return this;
  }

  clearHighlight(tabId: string): this {
    const tab = this.#el.querySelector<HTMLElement>(`[data-tab="${tabId}"]`);
    if (!tab) return this;

    const highlightClasses = [
      'text-red-600', 'dark:text-red-400', 'bg-red-100', 'dark:bg-red-900/30',
      'text-amber-600', 'dark:text-amber-400', 'bg-amber-100', 'dark:bg-amber-900/30',
      'text-green-600', 'dark:text-green-400', 'bg-green-100', 'dark:bg-green-900/30',
      'text-blue-600', 'dark:text-blue-400', 'bg-blue-100', 'dark:bg-blue-900/30',
      'tab-highlighted', 'animate-pulse'
    ];
    
    tab.classList.remove(...highlightClasses);
    delete tab.dataset.highlightType;
    delete tab.dataset.highlightPulse;

    tab.querySelector('[data-tab-badge]')?.remove();

    emit(this.#el, 'tabs:highlight-cleared', { tab: tabId });
    return this;
  }

  clearAllHighlights(): this {
    this.#getTabs().forEach(tab => {
      if (tab.dataset.tab) {
        this.clearHighlight(tab.dataset.tab);
      }
    });
    return this;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
