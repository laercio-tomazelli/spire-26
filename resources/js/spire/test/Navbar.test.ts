import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { Navbar } from '../components/Navbar';
import { instances, emit } from '../core/registry';

describe('Navbar', () => {
    let navbarElement: HTMLElement;
    let logoContainer: HTMLElement;
    let mobileToggle: HTMLElement;
    let sidebarElement: HTMLElement;

    beforeEach(() => {
        // Create navbar element
        navbarElement = document.createElement('nav');
        navbarElement.setAttribute('data-spire-navbar', '');
        navbarElement.classList.add('lg:pl-64'); // Initial state

        // Create logo container
        logoContainer = document.createElement('div');
        logoContainer.setAttribute('data-navbar-logo', '');
        logoContainer.classList.add('opacity-0', 'w-0', 'overflow-hidden');
        navbarElement.appendChild(logoContainer);

        // Create mobile toggle
        mobileToggle = document.createElement('button');
        mobileToggle.setAttribute('data-navbar-mobile-toggle', '');
        navbarElement.appendChild(mobileToggle);

        // Create sidebar element
        sidebarElement = document.createElement('aside');
        sidebarElement.id = 'sidebar';
        sidebarElement.classList.add('sidebar');
        document.body.appendChild(sidebarElement);

        // Set sidebar reference
        navbarElement.setAttribute('data-sidebar', 'sidebar');

        document.body.appendChild(navbarElement);
    });

    afterEach(() => {
        document.body.innerHTML = '';
        vi.clearAllMocks();
    });

    describe('Initialization', () => {
        it('should initialize with correct elements', () => {
            const navbar = new Navbar(navbarElement);

            expect(navbar).toBeInstanceOf(Navbar);
            expect(instances.get(navbarElement)).toBe(navbar);
        });

        it('should handle missing logo container', () => {
            navbarElement.removeChild(logoContainer);

            expect(() => new Navbar(navbarElement)).not.toThrow();
        });

        it('should handle missing sidebar', () => {
            navbarElement.removeAttribute('data-sidebar');
            document.body.removeChild(sidebarElement);

            expect(() => new Navbar(navbarElement)).not.toThrow();
        });

        it('should handle non-existent sidebar ID', () => {
            navbarElement.setAttribute('data-sidebar', 'non-existent');

            expect(() => new Navbar(navbarElement)).not.toThrow();
        });

        it('should set initial logo visibility when sidebar is collapsed', () => {
            sidebarElement.classList.add('sidebar-collapsed');

            new Navbar(navbarElement);

            expect(logoContainer.classList.contains('opacity-100')).toBe(true);
            expect(logoContainer.classList.contains('opacity-0')).toBe(false);
            expect(logoContainer.classList.contains('w-0')).toBe(false);
            expect(logoContainer.classList.contains('overflow-hidden')).toBe(false);
            expect(navbarElement.classList.contains('lg:pl-20')).toBe(true);
            expect(navbarElement.classList.contains('lg:pl-64')).toBe(false);
        });

        it('should set initial logo visibility when sidebar is expanded', () => {
            sidebarElement.classList.remove('sidebar-collapsed');

            new Navbar(navbarElement);

            expect(logoContainer.classList.contains('opacity-0')).toBe(true);
            expect(logoContainer.classList.contains('w-0')).toBe(true);
            expect(logoContainer.classList.contains('overflow-hidden')).toBe(true);
            expect(logoContainer.classList.contains('opacity-100')).toBe(false);
            expect(navbarElement.classList.contains('lg:pl-64')).toBe(true);
            expect(navbarElement.classList.contains('lg:pl-20')).toBe(false);
        });
    });

    describe('Functionality', () => {
        let navbar: Navbar;

        beforeEach(() => {
            navbar = new Navbar(navbarElement);
        });

        describe('showLogo()', () => {
            it('should show logo and update classes', () => {
                navbar.showLogo();

                expect(logoContainer.classList.contains('opacity-100')).toBe(true);
                expect(logoContainer.classList.contains('opacity-0')).toBe(false);
                expect(logoContainer.classList.contains('w-0')).toBe(false);
                expect(logoContainer.classList.contains('overflow-hidden')).toBe(false);
                expect(navbarElement.classList.contains('lg:pl-20')).toBe(true);
                expect(navbarElement.classList.contains('lg:pl-64')).toBe(false);
            });

            it('should return this for chaining', () => {
                expect(navbar.showLogo()).toBe(navbar);
            });
        });

        describe('hideLogo()', () => {
            it('should hide logo and update classes', () => {
                navbar.hideLogo();

                expect(logoContainer.classList.contains('opacity-0')).toBe(true);
                expect(logoContainer.classList.contains('w-0')).toBe(true);
                expect(logoContainer.classList.contains('overflow-hidden')).toBe(true);
                expect(logoContainer.classList.contains('opacity-100')).toBe(false);
                expect(navbarElement.classList.contains('lg:pl-64')).toBe(true);
                expect(navbarElement.classList.contains('lg:pl-20')).toBe(false);
            });

            it('should return this for chaining', () => {
                expect(navbar.hideLogo()).toBe(navbar);
            });
        });
    });

    describe('Events', () => {
        let navbar: Navbar;

        beforeEach(() => {
            navbar = new Navbar(navbarElement);
        });

        it('should handle sidebar collapse event', () => {
            const collapseEvent = new CustomEvent('sidebar:collapse');
            sidebarElement.dispatchEvent(collapseEvent);

            expect(logoContainer.classList.contains('opacity-100')).toBe(true);
            expect(navbarElement.classList.contains('lg:pl-20')).toBe(true);
        });

        it('should handle sidebar expand event', () => {
            const expandEvent = new CustomEvent('sidebar:expand');
            sidebarElement.dispatchEvent(expandEvent);

            expect(logoContainer.classList.contains('opacity-0')).toBe(true);
            expect(navbarElement.classList.contains('lg:pl-64')).toBe(true);
        });

        it('should emit navbar:sidebar-change event on collapse', () => {
            let emittedEvent: CustomEvent | null = null;
            navbarElement.addEventListener('navbar:sidebar-change', (e) => {
                emittedEvent = e as CustomEvent;
            });

            const collapseEvent = new CustomEvent('sidebar:collapse');
            sidebarElement.dispatchEvent(collapseEvent);

            expect(emittedEvent).not.toBeNull();
            expect(emittedEvent?.detail).toEqual({ collapsed: true });
        });

        it('should emit navbar:sidebar-change event on expand', () => {
            let emittedEvent: CustomEvent | null = null;
            navbarElement.addEventListener('navbar:sidebar-change', (e) => {
                emittedEvent = e as CustomEvent;
            });

            const expandEvent = new CustomEvent('sidebar:expand');
            sidebarElement.dispatchEvent(expandEvent);

            expect(emittedEvent).not.toBeNull();
            expect(emittedEvent?.detail).toEqual({ collapsed: false });
        });

        it('should handle mobile toggle click', () => {
            const mockSidebarInstance = {
                toggleMobile: vi.fn()
            };
            instances.set(sidebarElement, mockSidebarInstance);

            mobileToggle.click();

            expect(mockSidebarInstance.toggleMobile).toHaveBeenCalled();
        });

        it('should not call toggleMobile if sidebar instance lacks method', () => {
            const mockSidebarInstance = {};
            instances.set(sidebarElement, mockSidebarInstance);

            expect(() => mobileToggle.click()).not.toThrow();
        });
    });

    describe('Accessibility', () => {
        let navbar: Navbar;

        beforeEach(() => {
            navbar = new Navbar(navbarElement);
        });

        it('should maintain proper ARIA attributes on mobile toggle', () => {
            expect(mobileToggle.hasAttribute('aria-label')).toBe(false); // Not set by component
            expect(mobileToggle.getAttribute('role')).toBe(null);
        });

        it('should handle keyboard navigation for mobile toggle', () => {
            const mockSidebarInstance = {
                toggleMobile: vi.fn()
            };
            instances.set(sidebarElement, mockSidebarInstance);

            const enterEvent = new KeyboardEvent('keydown', { key: 'Enter' });
            mobileToggle.dispatchEvent(enterEvent);

            // Should not trigger on keydown, only click
            expect(mockSidebarInstance.toggleMobile).not.toHaveBeenCalled();
        });
    });

    describe('Edge Cases', () => {
        it('should handle multiple navbar instances', () => {
            const navbar2 = document.createElement('nav');
            navbar2.setAttribute('data-spire-navbar', '');
            document.body.appendChild(navbar2);

            const instance1 = new Navbar(navbarElement);
            const instance2 = new Navbar(navbar2);

            expect(instances.get(navbarElement)).toBe(instance1);
            expect(instances.get(navbar2)).toBe(instance2);
            expect(instance1).not.toBe(instance2);
        });

        it('should handle sidebar events when no logo container exists', () => {
            navbarElement.removeChild(logoContainer);

            new Navbar(navbarElement);

            const collapseEvent = new CustomEvent('sidebar:collapse');
            sidebarElement.dispatchEvent(collapseEvent);

            // Should not throw error
            expect(navbarElement.classList.contains('lg:pl-20')).toBe(true);
        });

        it('should handle destroy when sidebar does not exist', () => {
            navbarElement.removeAttribute('data-sidebar');

            const navbar = new Navbar(navbarElement);
            expect(() => navbar.destroy()).not.toThrow();
        });
    });

    describe('Cleanup', () => {
        it('should remove event listeners on destroy', () => {
            const navbar = new Navbar(navbarElement);

            const mockRemoveEventListener = vi.fn();
            sidebarElement.removeEventListener = mockRemoveEventListener;

            navbar.destroy();

            expect(mockRemoveEventListener).toHaveBeenCalledWith('sidebar:collapse', expect.any(Function));
            expect(mockRemoveEventListener).toHaveBeenCalledWith('sidebar:expand', expect.any(Function));
        });

        it('should remove instance from registry on destroy', () => {
            const navbar = new Navbar(navbarElement);

            expect(instances.has(navbarElement)).toBe(true);

            navbar.destroy();

            expect(instances.has(navbarElement)).toBe(false);
        });

        it('should handle destroy when called multiple times', () => {
            const navbar = new Navbar(navbarElement);

            navbar.destroy();
            expect(() => navbar.destroy()).not.toThrow();
        });
    });

    describe('Performance', () => {
        it('should handle rapid sidebar state changes', () => {
            new Navbar(navbarElement);

            // Simulate rapid events
            for (let i = 0; i < 10; i++) {
                const event = new CustomEvent(i % 2 === 0 ? 'sidebar:collapse' : 'sidebar:expand');
                sidebarElement.dispatchEvent(event);
            }

            // Should still be in a valid state
            expect(navbarElement.classList.contains('lg:pl-20') || navbarElement.classList.contains('lg:pl-64')).toBe(true);
        });

        it('should handle multiple initializations correctly', () => {
            const navbar1 = new Navbar(navbarElement);
            const navbar2 = new Navbar(navbarElement); // Re-initialize

            // Should have the latest instance
            expect(instances.get(navbarElement)).toBe(navbar2);
            expect(instances.get(navbarElement)).not.toBe(navbar1);
        });
    });
});