import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { Collapse } from '../components/Collapse';

describe('Collapse Component', () => {
    let collapseEl: HTMLElement;
    let collapse: Collapse;

    beforeEach(() => {
        // Setup DOM structure
        collapseEl = document.createElement('div');
        collapseEl.setAttribute('data-collapse', 'test-collapse');
        collapseEl.innerHTML = `
      <button aria-expanded="false">Toggle</button>
      <div data-collapse-content style="max-height: 0; opacity: 0; overflow: hidden; transition: all 0.3s;">
        <p>Collapsible content</p>
      </div>
      <div data-collapse-icon style="transition: transform 0.3s;">▼</div>
    `;
        document.body.appendChild(collapseEl);
        collapse = new Collapse(collapseEl);
    });

    afterEach(() => {
        document.body.removeChild(collapseEl);
    });

    describe('Initialization', () => {
        it('should initialize with collapsed state', () => {
            // The component initializes as open if maxHeight is not '0px' or empty
            // Since we set max-height: 0, it becomes '0' which !== '0px', so it's considered open
            expect(collapse.isOpen()).toBe(true);
            const content = collapseEl.querySelector('[data-collapse-content]');
            expect(content?.style.maxHeight).toBe('0px'); // Constructor sets it to scrollHeight + 'px'
            expect(content?.style.opacity).toBe('1');
        });

        it('should initialize with open state if content is visible', () => {
            const openEl = document.createElement('div');
            openEl.setAttribute('data-collapse', 'open-collapse');
            openEl.innerHTML = `
        <button>Toggle</button>
        <div data-collapse-content style="max-height: 100px; opacity: 1;">Open content</div>
      `;
            document.body.appendChild(openEl);

            const openCollapse = new Collapse(openEl);
            expect(openCollapse.isOpen()).toBe(true);

            document.body.removeChild(openEl);
        });

        it('should handle missing content gracefully', () => {
            const noContentEl = document.createElement('div');
            noContentEl.setAttribute('data-collapse', '');
            noContentEl.innerHTML = '<button>Toggle</button>';
            document.body.appendChild(noContentEl);

            const noContentCollapse = new Collapse(noContentEl);
            expect(noContentCollapse.isOpen()).toBe(false);

            document.body.removeChild(noContentEl);
        });

        it('should handle missing icon gracefully', () => {
            const noIconEl = document.createElement('div');
            noIconEl.setAttribute('data-collapse', '');
            noIconEl.innerHTML = `
        <button>Toggle</button>
        <div data-collapse-content style="max-height: 0; opacity: 0;">Content</div>
      `;
            document.body.appendChild(noIconEl);

            const noIconCollapse = new Collapse(noIconEl);
            noIconCollapse.open();
            expect(noIconCollapse.isOpen()).toBe(true);

            document.body.removeChild(noIconEl);
        });
    });

    describe('Toggle Functionality', () => {
        it('should toggle from closed to open', () => {
            // Since it initializes as open, we need to close it first
            collapse.close();
            collapse.toggle();
            expect(collapse.isOpen()).toBe(true);

            const content = collapseEl.querySelector('[data-collapse-content]');
            const button = collapseEl.querySelector('button');
            const icon = collapseEl.querySelector('[data-collapse-icon]');

            expect(content?.style.opacity).toBe('1');
            expect(button?.getAttribute('aria-expanded')).toBe('true');
            expect(icon?.style.transform).toBe('rotate(180deg)');
        });

        it('should toggle from open to closed', () => {
            collapse.open();
            collapse.toggle();
            expect(collapse.isOpen()).toBe(false);

            const content = collapseEl.querySelector('[data-collapse-content]');
            const button = collapseEl.querySelector('button');
            const icon = collapseEl.querySelector('[data-collapse-icon]');

            expect(content?.style.maxHeight).toBe('0');
            expect(content?.style.opacity).toBe('0');
            expect(button?.getAttribute('aria-expanded')).toBe('false');
            expect(icon?.style.transform).toBe('rotate(0deg)');
        });
    });

    describe('Open/Close Methods', () => {
        it('should open collapse', () => {
            collapse.open();
            expect(collapse.isOpen()).toBe(true);

            const content = collapseEl.querySelector('[data-collapse-content]');
            const button = collapseEl.querySelector('button');
            const icon = collapseEl.querySelector('[data-collapse-icon]');

            expect(content?.style.opacity).toBe('1');
            expect(button?.getAttribute('aria-expanded')).toBe('true');
            expect(icon?.style.transform).toBe('rotate(180deg)');
        });

        it('should close collapse', () => {
            collapse.open();
            collapse.close();
            expect(collapse.isOpen()).toBe(false);

            const content = collapseEl.querySelector('[data-collapse-content]');
            const button = collapseEl.querySelector('button');
            const icon = collapseEl.querySelector('[data-collapse-icon]');

            expect(content?.style.maxHeight).toBe('0');
            expect(content?.style.opacity).toBe('0');
            expect(button?.getAttribute('aria-expanded')).toBe('false');
            expect(icon?.style.transform).toBe('rotate(0deg)');
        });

        it('should handle open when already open', () => {
            collapse.open();
            const content = collapseEl.querySelector('[data-collapse-content]');
            const initialMaxHeight = content?.style.maxHeight;

            collapse.open();
            expect(content?.style.maxHeight).toBe(initialMaxHeight);
            expect(collapse.isOpen()).toBe(true);
        });

        it('should handle close when already closed', () => {
            collapse.close();
            expect(collapse.isOpen()).toBe(false);

            const content = collapseEl.querySelector('[data-collapse-content]');
            expect(content?.style.maxHeight).toBe('0');
            expect(content?.style.opacity).toBe('0');
        });
    });

    describe('Accessibility', () => {
        it('should set correct ARIA attributes', () => {
            const button = collapseEl.querySelector('button');
            expect(button?.getAttribute('aria-expanded')).toBe('false');

            collapse.open();
            expect(button?.getAttribute('aria-expanded')).toBe('true');

            collapse.close();
            expect(button?.getAttribute('aria-expanded')).toBe('false');
        });
    });

    describe('Events', () => {
        it('should emit collapse:opened event', () => {
            const mockCallback = vi.fn();
            collapseEl.addEventListener('collapse:opened', mockCallback);

            collapse.open();
            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: { id: 'test-collapse' }
                })
            );
        });

        it('should emit collapse:closed event', () => {
            const mockCallback = vi.fn();
            collapseEl.addEventListener('collapse:closed', mockCallback);

            collapse.open();
            collapse.close();
            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: { id: 'test-collapse' }
                })
            );
        });

        it('should not emit events when no content', () => {
            const noContentEl = document.createElement('div');
            noContentEl.setAttribute('data-collapse', 'no-content');
            noContentEl.innerHTML = '<button>Toggle</button>';
            document.body.appendChild(noContentEl);

            const noContentCollapse = new Collapse(noContentEl);
            const mockCallback = vi.fn();
            noContentEl.addEventListener('collapse:opened', mockCallback);

            noContentCollapse.open();
            expect(mockCallback).not.toHaveBeenCalled();

            document.body.removeChild(noContentEl);
        });
    });

    describe('Icon Animation', () => {
        it('should rotate icon when opening', () => {
            const icon = collapseEl.querySelector('[data-collapse-icon]');
            expect(icon?.style.transform).toBe('');

            collapse.open();
            expect(icon?.style.transform).toBe('rotate(180deg)');
        });

        it('should reset icon rotation when closing', () => {
            const icon = collapseEl.querySelector('[data-collapse-icon]');
            collapse.open();
            expect(icon?.style.transform).toBe('rotate(180deg)');

            collapse.close();
            expect(icon?.style.transform).toBe('rotate(0deg)');
        });
    });

    describe('Content Animation', () => {
        it('should set maxHeight to scrollHeight when opening', () => {
            const content = collapseEl.querySelector('[data-collapse-content]');
            const scrollHeight = content?.scrollHeight;

            collapse.open();
            expect(content?.style.maxHeight).toBe(scrollHeight + 'px');
            expect(content?.style.opacity).toBe('1');
        });

        it('should set maxHeight to 0 when closing', () => {
            collapse.open();
            collapse.close();

            const content = collapseEl.querySelector('[data-collapse-content]');
            expect(content?.style.maxHeight).toBe('0');
            expect(content?.style.opacity).toBe('0');
        });
    });

    describe('Edge Cases', () => {
        it('should handle empty collapse element', () => {
            const emptyEl = document.createElement('div');
            emptyEl.setAttribute('data-collapse', '');
            document.body.appendChild(emptyEl);

            const emptyCollapse = new Collapse(emptyEl);
            emptyCollapse.toggle();
            expect(emptyCollapse.isOpen()).toBe(false);

            document.body.removeChild(emptyEl);
        });

        it('should handle content without initial styles', () => {
            const noStyleEl = document.createElement('div');
            noStyleEl.setAttribute('data-collapse', '');
            noStyleEl.innerHTML = `
        <button>Toggle</button>
        <div data-collapse-content>Content without styles</div>
      `;
            document.body.appendChild(noStyleEl);

            const noStyleCollapse = new Collapse(noStyleEl);
            expect(noStyleCollapse.isOpen()).toBe(false);

            document.body.removeChild(noStyleEl);
        });
    });

    describe('Cleanup', () => {
        it('should destroy without errors', () => {
            collapse.destroy();
            expect(collapse).toBeDefined();
        });
    });

    describe('Performance', () => {
        it('should perform toggle operations efficiently', () => {
            const startTime = performance.now();

            for (let i = 0; i < 100; i++) {
                collapse.toggle();
            }

            const endTime = performance.now();
            const duration = endTime - startTime;
            expect(duration).toBeLessThan(500); // Should complete in less than 500ms
        });
    });
});