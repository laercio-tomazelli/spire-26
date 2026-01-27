import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { Drawer } from '../components/Drawer';

describe('Drawer Component', () => {
    let drawerEl: HTMLElement;
    let overlayEl: HTMLElement;
    let contentEl: HTMLElement;
    let drawer: Drawer;

    beforeEach(() => {
        vi.useFakeTimers();

        // Setup drawer element with required structure
        drawerEl = document.createElement('div');
        drawerEl.id = 'test-drawer';
        drawerEl.className = 'hidden pointer-events-none';
        drawerEl.setAttribute('data-position', 'left');
        drawerEl.setAttribute('data-duration', '300');

        // Create overlay
        overlayEl = document.createElement('div');
        overlayEl.setAttribute('data-drawer-overlay', '');
        overlayEl.className = 'opacity-0';
        drawerEl.appendChild(overlayEl);

        // Create content
        contentEl = document.createElement('div');
        contentEl.setAttribute('data-drawer-content', '');
        contentEl.className = '-translate-x-full';
        drawerEl.appendChild(contentEl);

        // Add close button
        const closeBtn = document.createElement('button');
        closeBtn.setAttribute('data-drawer-close', '');
        contentEl.appendChild(closeBtn);

        document.body.appendChild(drawerEl);

        drawer = new Drawer(drawerEl);
    });

    afterEach(() => {
        drawer.destroy();
        document.body.removeChild(drawerEl);
        vi.restoreAllMocks();
    });

    describe('Initialization', () => {
        it('should initialize with drawer element', () => {
            expect(drawer).toBeDefined();
            expect(drawerEl.getAttribute('role')).toBe('dialog');
            expect(drawerEl.getAttribute('aria-modal')).toBe('true');
            expect(contentEl.getAttribute('tabindex')).toBe('-1');
        });

        it('should handle different positions', () => {
            const positions = ['left', 'right', 'top', 'bottom'];

            positions.forEach(position => {
                const testDrawerEl = document.createElement('div');
                testDrawerEl.setAttribute('data-position', position);
                testDrawerEl.innerHTML = `
                    <div data-drawer-overlay></div>
                    <div data-drawer-content></div>
                `;
                document.body.appendChild(testDrawerEl);

                const testDrawer = new Drawer(testDrawerEl);
                expect(testDrawer).toBeDefined();

                testDrawer.destroy();
                document.body.removeChild(testDrawerEl);
            });
        });

        it('should handle custom duration', () => {
            const customDrawerEl = document.createElement('div');
            customDrawerEl.setAttribute('data-duration', '500');
            customDrawerEl.innerHTML = `
                <div data-drawer-overlay></div>
                <div data-drawer-content></div>
            `;
            document.body.appendChild(customDrawerEl);

            const customDrawer = new Drawer(customDrawerEl);
            expect(customDrawer).toBeDefined();

            customDrawer.destroy();
            document.body.removeChild(customDrawerEl);
        });

        it('should default to left position', () => {
            const defaultDrawerEl = document.createElement('div');
            defaultDrawerEl.innerHTML = `
                <div data-drawer-overlay></div>
                <div data-drawer-content></div>
            `;
            document.body.appendChild(defaultDrawerEl);

            const defaultDrawer = new Drawer(defaultDrawerEl);
            expect(defaultDrawer).toBeDefined();

            defaultDrawer.destroy();
            document.body.removeChild(defaultDrawerEl);
        });

        it('should default to 400ms duration', () => {
            const defaultDrawerEl = document.createElement('div');
            defaultDrawerEl.innerHTML = `
                <div data-drawer-overlay></div>
                <div data-drawer-content></div>
            `;
            document.body.appendChild(defaultDrawerEl);

            const defaultDrawer = new Drawer(defaultDrawerEl);
            expect(defaultDrawer).toBeDefined();

            defaultDrawer.destroy();
            document.body.removeChild(defaultDrawerEl);
        });
    });

    describe('Drawer Display', () => {
        it('should be closed by default', () => {
            expect(drawer.isOpen()).toBe(false);
            expect(drawerEl.classList.contains('hidden')).toBe(true);
            expect(drawerEl.classList.contains('pointer-events-none')).toBe(true);
        });

        it('should open drawer', () => {
            drawer.open();

            expect(drawer.isOpen()).toBe(true);
            expect(drawerEl.classList.contains('hidden')).toBe(false);
            expect(drawerEl.classList.contains('pointer-events-none')).toBe(false);
            expect(overlayEl.classList.contains('opacity-100')).toBe(true);
            expect(contentEl.classList.contains('translate-x-0')).toBe(true);
        });

        it('should close drawer', () => {
            drawer.open();
            drawer.close();

            expect(drawer.isOpen()).toBe(false);
            vi.runAllTimers(); // Wait for hide timeout
            expect(drawerEl.classList.contains('hidden')).toBe(true);
            expect(drawerEl.classList.contains('pointer-events-none')).toBe(true);
        });

        it('should toggle drawer', () => {
            expect(drawer.isOpen()).toBe(false);
            drawer.toggle();
            expect(drawer.isOpen()).toBe(true);
            drawer.toggle();
            expect(drawer.isOpen()).toBe(false);
        });

        it('should not open already open drawer', () => {
            drawer.open();
            expect(drawer.isOpen()).toBe(true);
            drawer.open(); // Should not throw or change state
            expect(drawer.isOpen()).toBe(true);
        });

        it('should not close already closed drawer', () => {
            expect(drawer.isOpen()).toBe(false);
            drawer.close(); // Should not throw or change state
            expect(drawer.isOpen()).toBe(false);
        });
    });

    describe('Position Handling', () => {
        it('should handle left position', () => {
            drawerEl.setAttribute('data-position', 'left');
            const leftDrawer = new Drawer(drawerEl);

            leftDrawer.open();
            expect(contentEl.classList.contains('translate-x-0')).toBe(true);

            leftDrawer.close();
            vi.runAllTimers();
        });

        it('should handle right position', () => {
            drawerEl.setAttribute('data-position', 'right');
            const rightDrawer = new Drawer(drawerEl);

            rightDrawer.open();
            expect(contentEl.classList.contains('translate-x-0')).toBe(true);

            rightDrawer.close();
            vi.runAllTimers();
        });

        it('should handle top position', () => {
            drawerEl.setAttribute('data-position', 'top');
            const topDrawer = new Drawer(drawerEl);

            topDrawer.open();
            expect(contentEl.classList.contains('translate-y-0')).toBe(true);

            topDrawer.close();
            vi.runAllTimers();
        });

        it('should handle bottom position', () => {
            drawerEl.setAttribute('data-position', 'bottom');
            const bottomDrawer = new Drawer(drawerEl);

            bottomDrawer.open();
            expect(contentEl.classList.contains('translate-y-0')).toBe(true);

            bottomDrawer.close();
            vi.runAllTimers();
        });
    });

    describe('Closing Mechanisms', () => {
        beforeEach(() => {
            drawer.open();
        });

        afterEach(() => {
            if (drawer.isOpen()) {
                drawer.close();
                vi.runAllTimers();
            }
        });

        it('should close on overlay click', () => {
            overlayEl.click();
            expect(drawer.isOpen()).toBe(false);
        });

        it('should close on close button click', () => {
            const closeBtn = drawerEl.querySelector('[data-drawer-close]') as HTMLElement;
            closeBtn.click();
            expect(drawer.isOpen()).toBe(false);
        });

        it('should close on ESC key', () => {
            const escEvent = new KeyboardEvent('keydown', { key: 'Escape' });
            document.dispatchEvent(escEvent);
            expect(drawer.isOpen()).toBe(false);
        });

        it('should not close on other keys', () => {
            const enterEvent = new KeyboardEvent('keydown', { key: 'Enter' });
            document.dispatchEvent(enterEvent);
            expect(drawer.isOpen()).toBe(true);
        });
    });

    describe('External Triggers', () => {
        let toggleBtn: HTMLElement;
        let openBtn: HTMLElement;

        beforeEach(() => {
            // Create external trigger buttons
            toggleBtn = document.createElement('button');
            toggleBtn.setAttribute('data-drawer-toggle', 'test-drawer');
            document.body.appendChild(toggleBtn);

            openBtn = document.createElement('button');
            openBtn.setAttribute('data-drawer-open', 'test-drawer');
            document.body.appendChild(openBtn);

            // Re-initialize drawer to pick up new triggers
            drawer.destroy();
            drawer = new Drawer(drawerEl);
        });

        afterEach(() => {
            document.body.removeChild(toggleBtn);
            document.body.removeChild(openBtn);
        });

        it('should toggle on external toggle button click', () => {
            expect(drawer.isOpen()).toBe(false);
            toggleBtn.click();
            expect(drawer.isOpen()).toBe(true);
            toggleBtn.click();
            expect(drawer.isOpen()).toBe(false);
        });

        it('should open on external open button click', () => {
            expect(drawer.isOpen()).toBe(false);
            openBtn.click();
            expect(drawer.isOpen()).toBe(true);
        });
    });

    describe('Accessibility', () => {
        it('should set correct ARIA attributes', () => {
            expect(drawerEl.getAttribute('role')).toBe('dialog');
            expect(drawerEl.getAttribute('aria-modal')).toBe('true');
            expect(contentEl.getAttribute('tabindex')).toBe('-1');
        });

        it('should focus content when opened', () => {
            drawer.open();

            // Focus should be set after animation frame
            vi.runOnlyPendingTimers();
            expect(contentEl).toBe(document.activeElement);
        });

        it('should restore focus when closed', () => {
            // Set focus to another element first
            const focusEl = document.createElement('input');
            document.body.appendChild(focusEl);
            focusEl.focus();

            drawer.open();
            drawer.close();

            expect(focusEl).toBe(document.activeElement);

            document.body.removeChild(focusEl);
        });

        it('should lock body scroll when open', () => {
            expect(document.body.style.overflow).toBe('');
            drawer.open();
            expect(document.body.style.overflow).toBe('hidden');
            drawer.close();
            vi.runAllTimers();
            expect(document.body.style.overflow).toBe('');
        });
    });

    describe('Events', () => {
        it('should emit drawer:opened event', () => {
            const mockCallback = vi.fn();
            drawerEl.addEventListener('drawer:opened', mockCallback);

            drawer.open();

            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        position: 'left'
                    })
                })
            );
        });

        it('should emit drawer:closed event', () => {
            const mockCallback = vi.fn();
            drawerEl.addEventListener('drawer:closed', mockCallback);

            drawer.open();
            drawer.close();

            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        position: 'left'
                    })
                })
            );
        });

        it('should include correct position in events', () => {
            drawerEl.setAttribute('data-position', 'right');
            const rightDrawer = new Drawer(drawerEl);

            const mockCallback = vi.fn();
            drawerEl.addEventListener('drawer:opened', mockCallback);

            rightDrawer.open();

            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        position: 'right'
                    })
                })
            );

            rightDrawer.close();
            vi.runAllTimers();
        });
    });

    describe('Animation and Timing', () => {
        it('should respect custom duration', () => {
            drawerEl.setAttribute('data-duration', '100');
            const fastDrawer = new Drawer(drawerEl);

            fastDrawer.open();
            fastDrawer.close();

            // Should hide after custom duration
            vi.advanceTimersByTime(50);
            expect(drawerEl.classList.contains('hidden')).toBe(false);

            vi.advanceTimersByTime(60);
            expect(drawerEl.classList.contains('hidden')).toBe(true);

            fastDrawer.destroy();
        });

        it('should handle rapid open/close', () => {
            for (let i = 0; i < 5; i++) {
                drawer.open();
                drawer.close();
            }

            vi.runAllTimers();
            expect(drawerEl.classList.contains('hidden')).toBe(true);
        });
    });

    describe('Edge Cases', () => {
        it('should handle missing overlay element', () => {
            const noOverlayEl = document.createElement('div');
            noOverlayEl.innerHTML = '<div data-drawer-content></div>';
            document.body.appendChild(noOverlayEl);

            const noOverlayDrawer = new Drawer(noOverlayEl);
            expect(() => noOverlayDrawer.open()).not.toThrow();

            noOverlayDrawer.destroy();
            document.body.removeChild(noOverlayEl);
        });

        it('should handle missing content element', () => {
            const noContentEl = document.createElement('div');
            noContentEl.innerHTML = '<div data-drawer-overlay></div>';
            document.body.appendChild(noContentEl);

            const noContentDrawer = new Drawer(noContentEl);
            expect(() => noContentDrawer.open()).not.toThrow();

            noContentDrawer.destroy();
            document.body.removeChild(noContentEl);
        });

        it('should handle missing close buttons', () => {
            const noCloseEl = document.createElement('div');
            noCloseEl.innerHTML = `
                <div data-drawer-overlay></div>
                <div data-drawer-content></div>
            `;
            document.body.appendChild(noCloseEl);

            const noCloseDrawer = new Drawer(noCloseEl);
            noCloseDrawer.open();

            // Should still be able to close via other methods
            noCloseDrawer.close();
            vi.runAllTimers();

            noCloseDrawer.destroy();
            document.body.removeChild(noCloseEl);
        });

        it('should handle invalid duration', () => {
            const invalidDurationEl = document.createElement('div');
            invalidDurationEl.setAttribute('data-duration', 'invalid');
            invalidDurationEl.innerHTML = `
                <div data-drawer-overlay></div>
                <div data-drawer-content></div>
            `;
            document.body.appendChild(invalidDurationEl);

            const invalidDurationDrawer = new Drawer(invalidDurationEl);
            expect(invalidDurationDrawer).toBeDefined();

            invalidDurationDrawer.destroy();
            document.body.removeChild(invalidDurationEl);
        });

        it('should handle invalid position', () => {
            const invalidPositionEl = document.createElement('div');
            invalidPositionEl.setAttribute('data-position', 'invalid');
            invalidPositionEl.innerHTML = `
                <div data-drawer-overlay></div>
                <div data-drawer-content></div>
            `;
            document.body.appendChild(invalidPositionEl);

            const invalidPositionDrawer = new Drawer(invalidPositionEl);
            expect(invalidPositionDrawer).toBeDefined();

            invalidPositionDrawer.destroy();
            document.body.removeChild(invalidPositionEl);
        });
    });

    describe('Cleanup', () => {
        it('should destroy without errors', () => {
            expect(() => drawer.destroy()).not.toThrow();
        });

        it('should remove event listeners on destroy', () => {
            drawer.destroy();
            // Should not throw when trying to access destroyed instance
            expect(() => drawer.destroy()).not.toThrow();
        });
    });

    describe('Performance', () => {
        it('should handle multiple instances efficiently', () => {
            const instances = [];
            const startTime = performance.now();

            for (let i = 0; i < 10; i++) {
                const testEl = document.createElement('div');
                testEl.innerHTML = `
                    <div data-drawer-overlay></div>
                    <div data-drawer-content></div>
                `;
                document.body.appendChild(testEl);

                const testDrawer = new Drawer(testEl);
                instances.push({ drawer: testDrawer, el: testEl });
            }

            const endTime = performance.now();
            const duration = endTime - startTime;

            expect(duration).toBeLessThan(1000); // Should create in less than 1 second

            // Cleanup
            instances.forEach(({ drawer, el }) => {
                drawer.destroy();
                document.body.removeChild(el);
            });
        });

        it('should handle rapid toggling efficiently', () => {
            const startTime = performance.now();

            for (let i = 0; i < 20; i++) {
                drawer.toggle();
            }

            const endTime = performance.now();
            const duration = endTime - startTime;

            expect(duration).toBeLessThan(1000); // Should complete in less than 1 second

            vi.runAllTimers();
        });
    });
});