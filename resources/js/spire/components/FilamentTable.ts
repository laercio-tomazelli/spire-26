/**
 * FilamentTable Component
 *
 * A vanilla JavaScript table component with Filament-style features:
 * - AJAX filtering, searching, sorting, pagination
 * - Row selection (single/multiple)
 * - Column visibility toggle
 * - Bulk actions
 *
 * Uses Spire EventBus for communication and HTTP client for requests.
 */

import { events } from '../utilities/EventBus';
import { http } from '../utilities/Http';
import { dom } from '../utilities/Dom';

export interface FilamentTableConfig {
  /** Base URL for AJAX requests */
  url: string;
  /** Container element */
  container: HTMLElement;
  /** Content container selector (where table HTML is updated) */
  contentSelector?: string;
  /** Initial state */
  initialState?: Partial<FilamentTableState>;
  /** CSRF token for requests */
  csrfToken?: string;
  /** Callback after content is updated */
  onUpdate?: (state: FilamentTableState) => void;
}

export interface FilamentTableState {
  search: string;
  page: number;
  perPage: number;
  sortField: string;
  sortDirection: 'asc' | 'desc';
  filters: Record<string, string>;
  selected: string[];
  visibleColumns: Record<string, boolean>;
  loading: boolean;
}

export class FilamentTable {
  private config: FilamentTableConfig;
  private state: FilamentTableState;
  private unsubscribers: (() => void)[] = [];
  private debounceTimer: ReturnType<typeof setTimeout> | null = null;

  constructor(config: FilamentTableConfig) {
    this.config = {
      contentSelector: '.fi-ta-content',
      ...config,
    };

    this.state = {
      search: '',
      page: 1,
      perPage: 10,
      sortField: '',
      sortDirection: 'asc',
      filters: {},
      selected: [],
      visibleColumns: {},
      loading: false,
      ...config.initialState,
    };

    this.init();
  }

  private init(): void {
    this.setupEventListeners();
    this.setupWindowEventListeners();
  }

  /**
   * Setup EventBus listeners
   */
  private setupEventListeners(): void {
    // Pagination events
    this.unsubscribers.push(
      events.on('table:goto-page', (page) => this.gotoPage(page as number)),
      events.on('table:previous-page', () => this.previousPage()),
      events.on('table:next-page', () => this.nextPage()),
      events.on('table:per-page', (value) => this.changePerPage(Number(value))),

      // Sorting
      events.on('table:sort', (field) => this.sort(field as string)),

      // Selection
      events.on('table:toggle-page-selection', () => this.togglePageSelection()),
      events.on('table:toggle-selection', (key) => this.toggleSelection(key as string)),

      // Filters
      events.on('table:apply-filters', () => this.applyFilters()),
      events.on('table:filter-change', (data) => {
        const { key, value } = data as { key: string; value: string };
        this.setFilter(key, value);
      }),
      events.on('table:reset-filters', () => this.resetFilters()),

      // Columns
      events.on('table:toggle-column', (data) => {
        const { name, visible } = data as { name: string; visible: boolean };
        this.toggleColumn(name, visible);
      }),
      events.on('table:reset-columns', () => this.resetColumns())
    );
  }

  /**
   * Setup window event listeners for native dispatched events
   */
  private setupWindowEventListeners(): void {
    const windowEvents: Record<string, (e: CustomEvent) => void> = {
      'table-goto-page': (e) => this.gotoPage(e.detail.page),
      'table-previous-page': () => this.previousPage(),
      'table-next-page': () => this.nextPage(),
      'table-per-page': (e) => this.changePerPage(Number(e.detail.value)),
      'table-sort': (e) => this.sort(e.detail.field),
      'table-toggle-page-selection': () => this.togglePageSelection(),
      'table-toggle-selection': (e) => this.toggleSelection(e.detail.key),
      'table-apply-filters': () => this.applyFilters(),
      'table-filter-change': (e) => this.setFilter(e.detail.key, e.detail.value),
      'table-reset-filters': () => this.resetFilters(),
      'table-toggle-column': (e) => this.toggleColumn(e.detail.name, e.detail.visible),
      'table-reset-columns': () => this.resetColumns(),
      'table-search': (e) => this.setSearch(e.detail.value),
      'table-select-all': () => this.selectAll(),
      'table-deselect-all': () => this.clearSelection(),
    };

    Object.entries(windowEvents).forEach(([event, handler]) => {
      const listener = (e: Event) => handler(e as CustomEvent);
      window.addEventListener(event, listener);
      this.unsubscribers.push(() => window.removeEventListener(event, listener));
    });
  }

  // =====================
  // PAGINATION
  // =====================

  gotoPage(page: number): void {
    this.state.page = page;
    this.applyFilters();
  }

  previousPage(): void {
    if (this.state.page > 1) {
      this.state.page--;
      this.applyFilters();
    }
  }

  nextPage(): void {
    this.state.page++;
    this.applyFilters();
  }

  changePerPage(value: number): void {
    this.state.perPage = value;
    this.state.page = 1;
    this.applyFilters();
  }

  // =====================
  // SORTING
  // =====================

  sort(field: string): void {
    if (this.state.sortField === field) {
      this.state.sortDirection = this.state.sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
      this.state.sortField = field;
      this.state.sortDirection = 'asc';
    }
    this.applyFilters();
  }

  // =====================
  // SELECTION
  // =====================

  toggleSelection(key: string): void {
    const index = this.state.selected.indexOf(key);
    const isNowSelected = index === -1;

    if (isNowSelected) {
      this.state.selected.push(key);
    } else {
      this.state.selected.splice(index, 1);
    }

    this.updateRowSelectionState(key, isNowSelected);
    this.updateSelectAllCheckbox();
    this.updateBulkActionsVisibility();
  }

  togglePageSelection(): void {
    const pageKeys = this.getPageKeys();
    const allSelected = pageKeys.every((key) => this.state.selected.includes(key));
    const newState = !allSelected;

    if (allSelected) {
      this.state.selected = this.state.selected.filter((key) => !pageKeys.includes(key));
    } else {
      pageKeys.forEach((key) => {
        if (!this.state.selected.includes(key)) {
          this.state.selected.push(key);
        }
      });
    }

    pageKeys.forEach((key) => this.updateRowSelectionState(key, newState));
    this.updateSelectAllCheckbox();
    this.updateBulkActionsVisibility();
  }

  private updateRowSelectionState(key: string, isSelected: boolean): void {
    const row = this.config.container.querySelector(`tr[data-record-key="${key}"]`);
    const checkbox = this.config.container.querySelector(
      `.fi-ta-record-checkbox[value="${key}"]`
    ) as HTMLInputElement;

    if (row) {
      if (isSelected) {
        row.classList.add('bg-primary-50', 'dark:bg-primary-500/10');
      } else {
        row.classList.remove('bg-primary-50', 'dark:bg-primary-500/10');
      }
    }

    if (checkbox) {
      checkbox.checked = isSelected;
    }
  }

  private updateSelectAllCheckbox(): void {
    const checkbox = this.config.container.querySelector('.fi-ta-select-all') as HTMLInputElement;
    if (checkbox) {
      const pageKeys = this.getPageKeys();
      const selectedOnPage = pageKeys.filter((key) => this.state.selected.includes(key));
      checkbox.checked = pageKeys.length > 0 && selectedOnPage.length === pageKeys.length;
      checkbox.indeterminate =
        selectedOnPage.length > 0 && selectedOnPage.length < pageKeys.length;
    }
  }

  private updateBulkActionsVisibility(): void {
    const bulkActions = this.config.container.querySelector('.fi-ta-bulk-actions') as HTMLElement;
    const selectionIndicator = this.config.container.querySelector('.fi-ta-selection-indicator') as HTMLElement;

    dom.showIf(bulkActions, this.state.selected.length > 0, 'flex');
    dom.showIf(selectionIndicator, this.state.selected.length > 0, 'flex');

    // Update selection count text
    const countEl = this.config.container.querySelector('.fi-ta-selection-count');
    if (countEl) {
      const count = this.state.selected.length;
      countEl.textContent = `${count} ${count === 1 ? 'registro selecionado' : 'registros selecionados'}`;
    }
  }

  private getPageKeys(): string[] {
    return Array.from(
      this.config.container.querySelectorAll('.fi-ta-record-checkbox')
    ).map((el) => (el as HTMLInputElement).value);
  }

  getSelected(): string[] {
    return [...this.state.selected];
  }

  selectAll(): void {
    // This would need a backend call to get all IDs
    // For now, select all on current page
    const pageKeys = this.getPageKeys();
    pageKeys.forEach((key) => {
      if (!this.state.selected.includes(key)) {
        this.state.selected.push(key);
        this.updateRowSelectionState(key, true);
      }
    });
    this.updateSelectAllCheckbox();
    this.updateBulkActionsVisibility();
    events.emit('table:selection-changed', this.state.selected);
  }

  clearSelection(): void {
    const pageKeys = this.getPageKeys();
    pageKeys.forEach((key) => this.updateRowSelectionState(key, false));
    this.state.selected = [];
    this.updateSelectAllCheckbox();
    this.updateBulkActionsVisibility();
    events.emit('table:selection-changed', this.state.selected);
  }

  // =====================
  // FILTERS
  // =====================

  setFilter(key: string, value: string): void {
    this.state.filters[key] = value;
    this.state.page = 1;
    this.updateFilterBadge();

    // Update tabs visual state if status filter changed
    if (key === 'status') {
      this.updateTabsActiveState(value);
    }

    // Debounce filter changes
    if (this.debounceTimer) {
      clearTimeout(this.debounceTimer);
    }
    this.debounceTimer = setTimeout(() => this.applyFilters(), 100);
  }

  resetFilters(): void {
    this.state.filters = {};
    this.state.page = 1;

    // Reset select elements in filters panel
    this.config.container.querySelectorAll('.fi-ta-filters select').forEach((select) => {
      (select as HTMLSelectElement).value = '';
    });

    // Dispatch reset event for spire::select components
    window.dispatchEvent(
      new CustomEvent('select-reset', { detail: { name: '*' } })
    );

    this.updateFilterBadge();
    this.applyFilters();
  }

  private updateFilterBadge(): void {
    const badge = this.config.container.querySelector('[data-filter-badge]') as HTMLElement;
    if (!badge) return;

    // Count active filters (non-empty values, excluding 'status' which is for tabs)
    const activeCount = Object.entries(this.state.filters)
      .filter(([key, value]) => value !== '' && key !== 'status')
      .length;

    if (activeCount > 0) {
      badge.textContent = String(activeCount);
      badge.classList.remove('hidden');
    } else {
      badge.textContent = '';
      badge.classList.add('hidden');
    }
  }

  private updateTabsActiveState(activeStatus: string): void {
    const tabs = this.config.container.querySelectorAll('.fi-ta-tab');

    tabs.forEach((tab) => {
      const button = tab as HTMLButtonElement;
      // Extract status value from onclick handler
      const onclickAttr = button.getAttribute('onclick') || '';
      const match = onclickAttr.match(/value:\s*'([^']*)'/);
      const tabStatus = match ? match[1] : '';

      const isActive = tabStatus === activeStatus;
      const indicator = button.querySelector('span.absolute');

      // Update text colors
      if (isActive) {
        button.classList.remove('text-gray-500', 'hover:text-gray-700', 'dark:text-gray-400', 'dark:hover:text-gray-300');
        button.classList.add('text-blue-600', 'dark:text-blue-400');
      } else {
        button.classList.remove('text-blue-600', 'dark:text-blue-400');
        button.classList.add('text-gray-500', 'hover:text-gray-700', 'dark:text-gray-400', 'dark:hover:text-gray-300');
      }

      // Update indicator bar
      if (isActive && !indicator) {
        const newIndicator = document.createElement('span');
        newIndicator.className = 'absolute inset-x-0 bottom-0 h-0.5 bg-blue-600 dark:bg-blue-400';
        button.appendChild(newIndicator);
      } else if (!isActive && indicator) {
        indicator.remove();
      }
    });
  }

  setSearch(value: string): void {
    this.state.search = value;
    this.state.page = 1;
    // Debounce search
    if (this.debounceTimer) {
      clearTimeout(this.debounceTimer);
    }
    this.debounceTimer = setTimeout(() => this.applyFilters(), 300);
  }

  // =====================
  // COLUMN VISIBILITY
  // =====================

  toggleColumn(name: string, visible: boolean): void {
    this.state.visibleColumns[name] = visible;

    // Toggle visibility using data-column attribute
    this.config.container.querySelectorAll(`[data-column="${name}"]`).forEach((cell) => {
      (cell as HTMLElement).style.display = visible ? '' : 'none';
    });
  }

  resetColumns(): void {
    Object.keys(this.state.visibleColumns).forEach((key) => {
      this.state.visibleColumns[key] = true;
      this.config.container.querySelectorAll(`[data-column="${key}"]`).forEach((cell) => {
        (cell as HTMLElement).style.display = '';
      });
    });

    // Reset checkboxes in column manager
    this.config.container
      .querySelectorAll('.fi-ta-col-manager-item input[type="checkbox"]')
      .forEach((cb) => {
        const checkbox = cb as HTMLInputElement;
        if (!checkbox.disabled) checkbox.checked = true;
      });
  }

  // =====================
  // AJAX
  // =====================

  async applyFilters(): Promise<void> {
    this.state.loading = true;
    this.showLoading();

    try {
      const params = new URLSearchParams();

      // Add search
      if (this.state.search) {
        params.append('search', this.state.search);
      }

      // Add filters
      Object.entries(this.state.filters).forEach(([key, value]) => {
        if (value !== '') {
          params.append(key, value);
        }
      });

      // Add sorting
      if (this.state.sortField) {
        params.append('sort', this.state.sortField);
        params.append('direction', this.state.sortDirection);
      }

      // Add pagination
      params.append('page', String(this.state.page));
      params.append('per_page', String(this.state.perPage));

      const url = this.config.url + (params.toString() ? '?' + params.toString() : '');

      // Update browser URL
      window.history.replaceState({}, '', url);

      // Fetch HTML content
      const response = await fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          Accept: 'text/html',
        },
      });

      if (response.ok) {
        const html = await response.text();
        const content = this.config.container.querySelector(this.config.contentSelector!);
        if (content) {
          content.innerHTML = html;
        }

        // Emit update event
        events.emit('table:updated', this.state);

        // Call callback if provided
        if (this.config.onUpdate) {
          this.config.onUpdate(this.state);
        }
      }
    } catch (error) {
      console.error('FilamentTable filter error:', error);
      events.emit('table:error', error);
    } finally {
      this.state.loading = false;
      this.hideLoading();
    }
  }

  private showLoading(): void {
    // Add loading state to container
    this.config.container.classList.add('fi-ta-loading');

    // Show loading spinner if exists
    const spinner = this.config.container.querySelector('.fi-ta-loading-indicator');
    if (spinner) {
      dom.show(spinner as HTMLElement);
    }
  }

  private hideLoading(): void {
    this.config.container.classList.remove('fi-ta-loading');

    const spinner = this.config.container.querySelector('.fi-ta-loading-indicator');
    if (spinner) {
      dom.hide(spinner as HTMLElement);
    }
  }

  // =====================
  // PUBLIC API
  // =====================

  getState(): FilamentTableState {
    return { ...this.state };
  }

  setState(partial: Partial<FilamentTableState>): void {
    this.state = { ...this.state, ...partial };
  }

  refresh(): Promise<void> {
    return this.applyFilters();
  }

  destroy(): void {
    this.unsubscribers.forEach((unsub) => unsub());
    this.unsubscribers = [];

    if (this.debounceTimer) {
      clearTimeout(this.debounceTimer);
    }
  }
}

/**
 * Initialize FilamentTable from data attributes
 */
export function initFilamentTable(container: HTMLElement): FilamentTable | null {
  const url = container.dataset.tableUrl;
  if (!url) {
    console.warn('FilamentTable: data-table-url attribute is required');
    return null;
  }

  const initialState: Partial<FilamentTableState> = {};

  // Parse initial state from data attributes
  if (container.dataset.search) initialState.search = container.dataset.search;
  if (container.dataset.page) initialState.page = parseInt(container.dataset.page, 10);
  if (container.dataset.perPage) initialState.perPage = parseInt(container.dataset.perPage, 10);
  if (container.dataset.sortField) initialState.sortField = container.dataset.sortField;
  if (container.dataset.sortDirection)
    initialState.sortDirection = container.dataset.sortDirection as 'asc' | 'desc';

  return new FilamentTable({
    url,
    container,
    initialState,
  });
}

// Auto-initialize tables with data-filament-table attribute
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-filament-table]').forEach((el) => {
    initFilamentTable(el as HTMLElement);
  });
});
