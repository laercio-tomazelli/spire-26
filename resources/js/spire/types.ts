// =====================
// TYPES & INTERFACES
// =====================

export interface SpireUIInstance {
  destroy(): void;
}

export interface ButtonInstance extends SpireUIInstance {
  loading(state?: boolean): this;
  success(msg?: string, duration?: number): this;
  error(msg?: string, duration?: number): this;
  reset(): this;
}

export interface InputInstance extends SpireUIInstance {
  value(v: string): this;
  error(msg?: string): this;
  focus(): this;
  clear(): this;
  disable(state?: boolean): this;
}

export interface ModalInstance extends SpireUIInstance {
  open(): this;
  close(): this;
  title(text: string | HTMLElement): this;
  body(content: string | HTMLElement): this;
}

export interface DropdownInstance extends SpireUIInstance {
  open(): this;
  close(): this;
  toggle(): this;
}

export interface TableInstance extends SpireUIInstance {
  loading(state?: boolean): this;
  html(rows: string): this;
  empty(message?: string): this;
}

export interface TabsInstance extends SpireUIInstance {
  show(tabId: string): this;
  current(): string | null;
  disable(tabId: string): this;
  enable(tabId: string): this;
  hide(tabId: string): this;
  unhide(tabId: string): this;
  add(config: { name: string; label: string; content: string; position?: number; active?: boolean }): this;
  remove(tabId: string): this;
  list(): Array<{ name: string; label: string }>;
  highlight(tabId: string, options?: { type?: 'error' | 'warning' | 'success' | 'info'; pulse?: boolean; badge?: string | number }): this;
  clearHighlight(tabId: string): this;
  clearAllHighlights(): this;
}

export interface AccordionInstance extends SpireUIInstance {
  toggle(itemId?: string): this;
  open(itemId: string): this;
  close(itemId: string): this;
  openAll(): this;
  closeAll(): this;
}

export interface TooltipInstance extends SpireUIInstance {
  show(): this;
  hide(): this;
  update(content: string): this;
}

export interface SelectOption {
  value: string;
  label: string;
  disabled?: boolean;
}

export interface SelectInstance extends SpireUIInstance {
  value(): string;
  setValue(val: string): this;
  open(): this;
  close(): this;
  options(opts: SelectOption[]): this;
  disable(state?: boolean): this;
}

export interface MultiSelectInstance extends SpireUIInstance {
  value(): string[];
  setValue(vals: string[]): this;
  add(val: string): this;
  remove(val: string): this;
  toggle(val: string): this;
  clear(): this;
  selectAll(): this;
  open(): this;
  close(): this;
  options(opts: SelectOption[]): this;
  disable(state?: boolean): this;
}

export interface DrawerInstance extends SpireUIInstance {
  open(): this;
  close(): this;
  toggle(): this;
  isOpen(): boolean;
}

export interface PopoverInstance extends SpireUIInstance {
  show(): this;
  hide(): this;
  toggle(): this;
  update(content: string | HTMLElement): this;
}

export interface ProgressInstance extends SpireUIInstance {
  value(percent?: number): number | this;
  increment(amount?: number): this;
  decrement(amount?: number): this;
  complete(): this;
  reset(): this;
  indeterminate(state?: boolean): this;
}

export interface SkeletonInstance extends SpireUIInstance {
  show(): this;
  hide(): this;
  toggle(): this;
}

export interface ClipboardInstance extends SpireUIInstance {
  copy(): Promise<boolean>;
  copyText(text: string): Promise<boolean>;
}

export interface StepperInstance extends SpireUIInstance {
  current(): number;
  goto(step: number): this;
  next(): this;
  prev(): this;
  complete(step?: number): this;
  reset(): this;
  canNext(): boolean;
  canPrev(): boolean;
}

export interface ContextMenuItem {
  id: string;
  label: string;
  icon?: string;
  shortcut?: string;
  disabled?: boolean;
  danger?: boolean;
  divider?: boolean;
  handler?: () => void;
  items?: ContextMenuItem[];
}

export interface ContextMenuInstance extends SpireUIInstance {
  show(x: number, y: number, items?: ContextMenuItem[]): this;
  hide(): this;
  setItems(items: ContextMenuItem[]): this;
  isOpen(): boolean;
}

export interface FormValidatorInstance extends SpireUIInstance {
  validate(): boolean;
  isValid(): boolean;
  errors(): Record<string, string[]>;
  reset(): this;
  setRules(field: string, rules: string): this;
}

export interface LazyLoadInstance extends SpireUIInstance {
  load(): this;
  unload(): this;
  isLoaded(): boolean;
}

export interface InfiniteScrollInstance extends SpireUIInstance {
  load(): Promise<void>;
  reset(): this;
  setLoader(fn: () => Promise<string>): this;
  hasMore(): boolean;
}

export interface DatePickerInstance extends SpireUIInstance {
  value(): string;
  setValue(date: string | Date): this;
  open(): this;
  close(): this;
  min(date: string | Date): this;
  max(date: string | Date): this;
  disable(state?: boolean): this;
}

export interface DateRangePickerInstance extends SpireUIInstance {
  value(): { start: string | null; end: string | null };
  setValue(start: string | Date | null, end: string | Date | null): this;
  open(): this;
  close(): this;
  min(date: string | Date): this;
  max(date: string | Date): this;
  disable(state?: boolean): this;
  clear(): this;
}

export interface ColorPickerInstance extends SpireUIInstance {
  value(): string;
  setValue(color: string): this;
  open(): this;
  close(): this;
}

export interface RangeSliderInstance extends SpireUIInstance {
  value(): number | [number, number];
  setValue(val: number | [number, number]): this;
  min(val: number): this;
  max(val: number): this;
  step(val: number): this;
}

export interface FileUploadInstance extends SpireUIInstance {
  files(): File[];
  clear(): this;
  upload(): Promise<void>;
  remove(index: number): this;
}

export interface CommandItem {
  id: string;
  title: string;
  description?: string;
  icon?: string;
  shortcut?: string;
  handler: () => void;
  category?: string;
}

export interface CommandPaletteInstance extends SpireUIInstance {
  open(): this;
  close(): this;
  toggle(): this;
  setCommands(commands: CommandItem[]): this;
  registerCommand(command: CommandItem): this;
}

export interface VirtualScrollInstance extends SpireUIInstance {
  setItems(items: unknown[]): this;
  scrollTo(index: number): this;
  refresh(): this;
}

export interface PersistInstance extends SpireUIInstance {
  save(): this;
  load(): this;
  clear(): this;
}

export interface ConfirmOptions {
  title?: string;
  message: string;
  confirmText?: string;
  cancelText?: string;
  confirmClass?: string;
  onConfirm?: () => void;
  onCancel?: () => void;
}

export type ToastType = 'success' | 'error' | 'info' | 'warning';

export interface ToastManager {
  show(msg: string, type?: ToastType, duration?: number): this;
  success(msg: string, duration?: number): this;
  error(msg: string, duration?: number): this;
  info(msg: string, duration?: number): this;
  warning(msg: string, duration?: number): this;
  clear(): void;
}

export interface KeyboardShortcut {
  key: string;
  ctrl?: boolean;
  alt?: boolean;
  shift?: boolean;
  meta?: boolean;
  handler: (e: KeyboardEvent) => void;
  description?: string;
}

export interface KeyboardShortcutsManager {
  register(shortcut: KeyboardShortcut | string, handler?: () => void): void;
  unregister(key: string, modifiers?: { ctrl?: boolean; alt?: boolean; shift?: boolean; meta?: boolean }): void;
  list(): KeyboardShortcut[];
  enable(): void;
  disable(): void;
}

export interface MaskDefinition {
  pattern: string;
  placeholder?: string;
}

export interface CurrencyOptions {
  locale?: string;
  currency?: string;
  symbol?: string;
  decimal?: string;
  thousands?: string;
}

export interface HttpOptions {
  headers?: Record<string, string>;
  timeout?: number;
}

export interface HttpResponse<T = unknown> {
  data: T;
  status: number;
  ok: boolean;
}

export interface EventBusManager {
  on(event: string, handler: (...args: unknown[]) => void): () => void;
  off(event: string, handler?: (...args: unknown[]) => void): void;
  emit(event: string, ...args: unknown[]): void;
  once(event: string, handler: (...args: unknown[]) => void): () => void;
}

export interface HttpClient {
  get<T = unknown>(url: string, options?: HttpOptions): Promise<HttpResponse<T>>;
  post<T = unknown>(url: string, data?: unknown, options?: HttpOptions): Promise<HttpResponse<T>>;
  put<T = unknown>(url: string, data?: unknown, options?: HttpOptions): Promise<HttpResponse<T>>;
  patch<T = unknown>(url: string, data?: unknown, options?: HttpOptions): Promise<HttpResponse<T>>;
  delete<T = unknown>(url: string, options?: HttpOptions): Promise<HttpResponse<T>>;
}

export interface CurrencyManager {
  format(value: number, options?: CurrencyOptions): string;
  parse(value: string): number;
}

export interface MaskManager {
  apply(input: HTMLInputElement, mask: string | MaskDefinition): void;
  remove(input: HTMLInputElement): void;
  getValue(input: HTMLInputElement): string;
}

export interface PerfManager {
  mark(name: string): void;
  measure(name: string, startMark: string, endMark?: string): number;
  getMarks(): Record<string, number>;
  clear(): void;
}

export interface A11yManager {
  announce(message: string, priority?: 'polite' | 'assertive'): void;
  trapFocus(container: HTMLElement): () => void;
  skipLink(target: string, label?: string): void;
}

export interface DomManager {
  show(el: HTMLElement | null): void;
  hide(el: HTMLElement | null): void;
  toggle(el: HTMLElement | null): void;
  showIf(el: HTMLElement | null, condition: boolean): void;
  on<K extends keyof HTMLElementEventMap>(
    el: HTMLElement | null,
    event: K,
    handler: (event: HTMLElementEventMap[K]) => void
  ): void;
  onClick(el: HTMLElement | null, handler: (event: MouseEvent) => void): void;
  onChange(el: HTMLElement | null, handler: (event: Event) => void): void;
  onInput(el: HTMLElement | null, handler: (event: Event) => void): void;
  debounce<T extends (...args: unknown[]) => void>(fn: T, delay: number): T;
  createDropdown(trigger: HTMLElement, options?: { menu?: HTMLElement; closeOnClickOutside?: boolean }): { toggle: () => void; open: () => void; close: () => void };
}

export interface FilamentTableConfig {
  url: string;
  container: HTMLElement;
  contentSelector?: string;
  initialState?: Partial<FilamentTableState>;
  csrfToken?: string;
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

export interface FilamentTableConstructor {
  new(config: FilamentTableConfig): FilamentTableInstance;
}

export interface FilamentTableInstance {
  gotoPage(page: number): void;
  previousPage(): void;
  nextPage(): void;
  changePerPage(value: number): void;
  sort(field: string): void;
  toggleSelection(key: string): void;
  togglePageSelection(): void;
  selectAll(): void;
  deselectAll(): void;
  toggleColumn(name: string, visible: boolean): void;
  resetColumns(): void;
  setFilter(key: string, value: string): void;
  resetFilters(): void;
  applyFilters(): Promise<void>;
  getState(): FilamentTableState;
  getSelected(): string[];
  destroy(): void;
}

export type ErrorHandler = (error: Error, context?: { component?: string; element?: HTMLElement }) => void;

export interface CarouselInstance extends SpireUIInstance {
  next(): this;
  prev(): this;
  goto(index: number): this;
  current(): number;
  play(): this;
  pause(): this;
}

export interface RatingInstance extends SpireUIInstance {
  value(): number;
  setValue(val: number): this;
  disable(state?: boolean): this;
  readonly(state?: boolean): this;
}

export interface CollapseInstance extends SpireUIInstance {
  toggle(): this;
  open(): this;
  close(): this;
  isOpen(): boolean;
}

export interface SidebarInstance extends SpireUIInstance {
  toggle(): this;
  collapse(): this;
  expand(): this;
  openMobile(): this;
  closeMobile(): this;
  toggleMobile(): this;
  isCollapsed(): boolean;
  openSubmenu(itemId: string): this;
  closeSubmenu(itemId: string): this;
  closeAllSubmenus(): this;
}

export interface NavbarInstance extends SpireUIInstance {
  showLogo(): this;
  hideLogo(): this;
}

export interface SpireUIAPI {
  init(): void;
  get<T extends SpireUIInstance>(el: HTMLElement): T | undefined;
  destroy(el: HTMLElement): void;
  destroyAll(): void;
  collapse(id: string): CollapseInstance | null;
  carousel(id: string): CarouselInstance | null;
  toast: ToastManager;
  confirm(options: ConfirmOptions): Promise<boolean>;
  shortcuts: KeyboardShortcutsManager;
  command: CommandPaletteInstance;
  debounce<T extends (...args: unknown[]) => unknown>(fn: T, delay: number): T;
  throttle<T extends (...args: unknown[]) => unknown>(fn: T, limit: number): T;
  events: EventBusManager;
  http: HttpClient;
  currency: CurrencyManager;
  mask: MaskManager;
  perf: PerfManager;
  a11y: A11yManager;
  dom: DomManager;
  FilamentTable: FilamentTableConstructor;
  onError(handler: ErrorHandler): void;
}

// Extend HTMLElement to include component properties
declare global {
  interface HTMLElement {
    $button?: ButtonInstance;
    $input?: InputInstance;
    $modal?: ModalInstance;
    $dropdown?: DropdownInstance;
    $table?: TableInstance;
    $tabs?: TabsInstance;
    $accordion?: AccordionInstance;
    $tooltip?: TooltipInstance;
    $select?: SelectInstance;
    $multiselect?: MultiSelectInstance;
    $drawer?: DrawerInstance;
    $popover?: PopoverInstance;
    $progress?: ProgressInstance;
    $skeleton?: SkeletonInstance;
    $clipboard?: ClipboardInstance;
    $stepper?: StepperInstance;
    $form?: FormValidatorInstance;
    $lazy?: LazyLoadInstance;
    $infinitescroll?: InfiniteScrollInstance;
    $datepicker?: DatePickerInstance;
    $daterangepicker?: DateRangePickerInstance;
    $colorpicker?: ColorPickerInstance;
    $range?: RangeSliderInstance;
    $upload?: FileUploadInstance;
    $commandpalette?: CommandPaletteInstance;
    $virtualscroll?: VirtualScrollInstance;
    $persist?: PersistInstance;
    $contextmenu?: ContextMenuInstance;
    $carousel?: CarouselInstance;
    $rating?: RatingInstance;
    $collapse?: CollapseInstance;
  }
}
