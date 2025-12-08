import { NavbarInstance } from '../types';
import { instances, emit } from '../core/registry';

export class Navbar implements NavbarInstance {
  #el: HTMLElement;
  #logoContainer: HTMLElement | null;
  #sidebarId: string | null;
  #sidebar: HTMLElement | null;
  #boundHandleSidebarChange: (e: Event) => void;

  constructor(el: HTMLElement) {
    this.#el = el;
    this.#logoContainer = el.querySelector('[data-navbar-logo]');
    this.#sidebarId = el.dataset.sidebar || null;
    this.#sidebar = this.#sidebarId ? document.getElementById(this.#sidebarId) : null;
    
    this.#boundHandleSidebarChange = this.#handleSidebarChange.bind(this);
    
    this.#init();
    instances.set(el, this);
  }

  #init(): void {
    // Listen to sidebar collapse/expand events
    if (this.#sidebar) {
      this.#sidebar.addEventListener('sidebar:collapse', this.#boundHandleSidebarChange);
      this.#sidebar.addEventListener('sidebar:expand', this.#boundHandleSidebarChange);
      
      // Set initial state
      this.#updateLogoVisibility(this.#sidebar.classList.contains('sidebar-collapsed'));
    }

    // Setup mobile menu toggle
    const mobileToggle = this.#el.querySelector('[data-navbar-mobile-toggle]');
    mobileToggle?.addEventListener('click', () => {
      if (this.#sidebar) {
        const sidebarInstance = instances.get(this.#sidebar);
        if (sidebarInstance && 'toggleMobile' in sidebarInstance) {
          (sidebarInstance as { toggleMobile: () => void }).toggleMobile();
        }
      }
    });
  }

  #handleSidebarChange(e: Event): void {
    const isCollapsed = e.type === 'sidebar:collapse';
    this.#updateLogoVisibility(isCollapsed);
    emit(this.#el, 'navbar:sidebar-change', { collapsed: isCollapsed });
  }

  #updateLogoVisibility(sidebarCollapsed: boolean): void {
    if (this.#logoContainer) {
      if (sidebarCollapsed) {
        this.#logoContainer.classList.remove('opacity-0', 'w-0', 'overflow-hidden');
        this.#logoContainer.classList.add('opacity-100');
      } else {
        this.#logoContainer.classList.add('opacity-0', 'w-0', 'overflow-hidden');
        this.#logoContainer.classList.remove('opacity-100');
      }
    }
    
    // Update navbar left padding based on sidebar state
    if (sidebarCollapsed) {
      this.#el.classList.remove('lg:pl-64');
      this.#el.classList.add('lg:pl-20');
    } else {
      this.#el.classList.remove('lg:pl-20');
      this.#el.classList.add('lg:pl-64');
    }
  }

  // Public API

  showLogo(): this {
    this.#updateLogoVisibility(true);
    return this;
  }

  hideLogo(): this {
    this.#updateLogoVisibility(false);
    return this;
  }

  destroy(): void {
    if (this.#sidebar) {
      this.#sidebar.removeEventListener('sidebar:collapse', this.#boundHandleSidebarChange);
      this.#sidebar.removeEventListener('sidebar:expand', this.#boundHandleSidebarChange);
    }
    instances.delete(this.#el);
  }
}
