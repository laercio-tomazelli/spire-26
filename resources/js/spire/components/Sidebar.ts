import { SidebarInstance } from '../types';
import { instances, emit } from '../core/registry';

export class Sidebar implements SidebarInstance {
  #el: HTMLElement;
  #toggleBtns: NodeListOf<HTMLElement>;
  #overlay: HTMLElement | null;
  #menuItems: NodeListOf<HTMLElement>;
  #isCollapsed: boolean;
  #isMobileOpen: boolean;
  #persistKey: string | null;
  #boundHandleResize: () => void;
  #boundHandleKeydown: (e: KeyboardEvent) => void;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#toggleBtns = el.querySelectorAll('[data-sidebar-toggle]');
    this.#overlay = el.querySelector('[data-sidebar-overlay]');
    this.#menuItems = el.querySelectorAll('[data-sidebar-item]');
    this.#persistKey = el.dataset.persist || null;
    this.#isCollapsed = this.#loadState();
    this.#isMobileOpen = false;
    
    this.#boundHandleResize = this.#handleResize.bind(this);
    this.#boundHandleKeydown = this.#handleKeydown.bind(this);
    
    this.#init();
    instances.set(el, this);
  }

  #init(): void {
    // Apply initial state
    if (this.#isCollapsed) {
      this.#el.classList.add('sidebar-collapsed');
    }

    // Setup toggle buttons (can be multiple)
    this.#toggleBtns.forEach(btn => {
      btn.addEventListener('click', () => this.toggle());
    });

    // Setup overlay click to close on mobile
    this.#overlay?.addEventListener('click', () => this.closeMobile());

    // Setup menu items with submenus
    this.#menuItems.forEach(item => {
      const submenuTrigger = item.querySelector('[data-submenu-trigger]');
      const submenu = item.querySelector('[data-submenu]');
      
      if (submenuTrigger && submenu) {
        submenuTrigger.addEventListener('click', (e) => {
          e.preventDefault();
          this.#toggleSubmenu(item, submenu as HTMLElement);
        });
      }
    });

    // Keyboard navigation
    document.addEventListener('keydown', this.#boundHandleKeydown);

    // Handle resize
    window.addEventListener('resize', this.#boundHandleResize);

    // Setup accessibility
    this.#setupA11y();
  }

  #setupA11y(): void {
    this.#el.setAttribute('role', 'navigation');
    this.#el.setAttribute('aria-label', 'Menu principal');
    
    this.#menuItems.forEach(item => {
      const submenu = item.querySelector('[data-submenu]');
      const trigger = item.querySelector('[data-submenu-trigger]');
      
      if (submenu && trigger) {
        const submenuId = submenu.id || `submenu-${Math.random().toString(36).substring(2, 9)}`;
        submenu.id = submenuId;
        trigger.setAttribute('aria-expanded', 'false');
        trigger.setAttribute('aria-controls', submenuId);
        submenu.setAttribute('role', 'menu');
      }
    });
  }

  #toggleSubmenu(item: HTMLElement, submenu: HTMLElement): void {
    const isOpen = item.classList.contains('submenu-open');
    const trigger = item.querySelector('[data-submenu-trigger]');
    const arrow = trigger?.querySelector('[data-arrow]');

    // Close other open submenus at the same level
    const siblings = item.parentElement?.querySelectorAll(':scope > [data-sidebar-item].submenu-open');
    siblings?.forEach(sibling => {
      if (sibling !== item) {
        this.#closeSubmenu(sibling as HTMLElement);
      }
    });

    if (isOpen) {
      this.#closeSubmenu(item);
    } else {
      // Open submenu with slide animation
      item.classList.add('submenu-open');
      trigger?.setAttribute('aria-expanded', 'true');
      arrow?.classList.add('rotate-90');
      
      // Animate height
      submenu.style.height = '0';
      submenu.style.overflow = 'hidden';
      submenu.classList.remove('hidden');
      
      const height = submenu.scrollHeight;
      submenu.style.transition = 'height 0.3s ease-out';
      submenu.style.height = `${height}px`;
      
      submenu.addEventListener('transitionend', () => {
        submenu.style.height = '';
        submenu.style.overflow = '';
        submenu.style.transition = '';
      }, { once: true });

      emit(this.#el, 'sidebar:submenu-open', { item, submenu });
    }
  }

  #closeSubmenu(item: HTMLElement): void {
    const submenu = item.querySelector('[data-submenu]') as HTMLElement;
    const trigger = item.querySelector('[data-submenu-trigger]');
    const arrow = trigger?.querySelector('[data-arrow]');

    if (!submenu) return;

    // Animate height to 0
    const height = submenu.scrollHeight;
    submenu.style.height = `${height}px`;
    submenu.style.overflow = 'hidden';
    submenu.style.transition = 'height 0.3s ease-out';
    
    // Force reflow
    submenu.offsetHeight;
    
    submenu.style.height = '0';

    submenu.addEventListener('transitionend', () => {
      submenu.classList.add('hidden');
      submenu.style.height = '';
      submenu.style.overflow = '';
      submenu.style.transition = '';
      item.classList.remove('submenu-open');
      trigger?.setAttribute('aria-expanded', 'false');
      arrow?.classList.remove('rotate-90');
    }, { once: true });

    emit(this.#el, 'sidebar:submenu-close', { item, submenu });
  }

  #handleResize(): void {
    const isMobile = window.innerWidth < 1024;
    
    if (!isMobile && this.#isMobileOpen) {
      this.closeMobile();
    }
  }

  #handleKeydown(e: KeyboardEvent): void {
    // Close mobile sidebar on Escape
    if (e.key === 'Escape' && this.#isMobileOpen) {
      this.closeMobile();
    }
  }

  #loadState(): boolean {
    if (!this.#persistKey) return false;
    return localStorage.getItem(`sidebar-${this.#persistKey}-collapsed`) === 'true';
  }

  #saveState(): void {
    if (!this.#persistKey) return;
    localStorage.setItem(`sidebar-${this.#persistKey}-collapsed`, String(this.#isCollapsed));
  }

  // Public API

  toggle(): this {
    if (this.#isCollapsed) {
      this.expand();
    } else {
      this.collapse();
    }
    return this;
  }

  collapse(): this {
    this.#isCollapsed = true;
    this.#el.classList.add('sidebar-collapsed');
    this.#saveState();
    
    // Close all submenus when collapsing
    this.#el.querySelectorAll('.submenu-open').forEach(item => {
      this.#closeSubmenu(item as HTMLElement);
    });

    emit(this.#el, 'sidebar:collapse', { collapsed: true });
    return this;
  }

  expand(): this {
    this.#isCollapsed = false;
    this.#el.classList.remove('sidebar-collapsed');
    this.#saveState();
    emit(this.#el, 'sidebar:expand', { collapsed: false });
    return this;
  }

  openMobile(): this {
    this.#isMobileOpen = true;
    this.#el.classList.add('sidebar-mobile-open');
    this.#overlay?.classList.remove('hidden');
    this.#overlay?.classList.add('opacity-100');
    document.body.style.overflow = 'hidden';
    emit(this.#el, 'sidebar:mobile-open', {});
    return this;
  }

  closeMobile(): this {
    this.#isMobileOpen = false;
    this.#overlay?.classList.remove('opacity-100');
    this.#overlay?.classList.add('opacity-0');
    
    setTimeout(() => {
      this.#el.classList.remove('sidebar-mobile-open');
      this.#overlay?.classList.add('hidden');
      document.body.style.overflow = '';
    }, 300);
    
    emit(this.#el, 'sidebar:mobile-close', {});
    return this;
  }

  toggleMobile(): this {
    if (this.#isMobileOpen) {
      this.closeMobile();
    } else {
      this.openMobile();
    }
    return this;
  }

  isCollapsed(): boolean {
    return this.#isCollapsed;
  }

  openSubmenu(itemId: string): this {
    const item = this.#el.querySelector(`[data-sidebar-item="${itemId}"]`) as HTMLElement;
    const submenu = item?.querySelector('[data-submenu]') as HTMLElement;
    
    if (item && submenu && !item.classList.contains('submenu-open')) {
      this.#toggleSubmenu(item, submenu);
    }
    return this;
  }

  closeSubmenu(itemId: string): this {
    const item = this.#el.querySelector(`[data-sidebar-item="${itemId}"]`) as HTMLElement;
    
    if (item?.classList.contains('submenu-open')) {
      this.#closeSubmenu(item);
    }
    return this;
  }

  closeAllSubmenus(): this {
    this.#el.querySelectorAll('.submenu-open').forEach(item => {
      this.#closeSubmenu(item as HTMLElement);
    });
    return this;
  }

  destroy(): void {
    window.removeEventListener('resize', this.#boundHandleResize);
    document.removeEventListener('keydown', this.#boundHandleKeydown);
    instances.delete(this.#el);
  }
}
