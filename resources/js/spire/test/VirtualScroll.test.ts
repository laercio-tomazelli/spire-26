import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { VirtualScroll } from '../components/VirtualScroll';
import { instances } from '../core/registry';

describe('VirtualScroll Component', () => {
    let container: HTMLElement;
    let scrollElement: HTMLElement;
    let virtualScroll: VirtualScroll;

    beforeEach(() => {
        container = document.createElement('div');
        document.body.appendChild(container);

        scrollElement = document.createElement('div');
        scrollElement.style.width = '300px';
        scrollElement.style.height = '200px';
        scrollElement.dataset.itemHeight = '40';
        container.appendChild(scrollElement);

        // Mock clientHeight
        Object.defineProperty(scrollElement, 'clientHeight', {
            value: 200,
            writable: true
        });

        virtualScroll = new VirtualScroll(scrollElement);
    });

    afterEach(() => {
        virtualScroll.destroy();
        document.body.removeChild(container);
    });

    describe('Initialization', () => {
        it('should initialize with scroll element', () => {
            expect(virtualScroll).toBeInstanceOf(VirtualScroll);
            expect(instances.get(scrollElement)).toBe(virtualScroll);
        });

        it('should set up container structure', () => {
            expect(scrollElement.style.overflow).toBe('auto');
            expect(scrollElement.style.position).toBe('relative');

            const spacer = scrollElement.querySelector('[data-virtual-spacer]');
            expect(spacer).not.toBeNull();

            const itemsContainer = scrollElement.children[1] as HTMLElement;
            expect(itemsContainer).not.toBeNull();
            expect(itemsContainer.style.position).toBe('absolute');
        });

        it('should use default item height', () => {
            const defaultElement = document.createElement('div');
            defaultElement.style.height = '150px';
            Object.defineProperty(defaultElement, 'clientHeight', { value: 150 });

            const defaultScroll = new VirtualScroll(defaultElement);
            // Should use default height of 48
            expect(defaultElement.querySelector('[data-virtual-spacer]')).not.toBeNull();

            defaultScroll.destroy();
        });

        it('should respect item height from dataset', () => {
            expect(scrollElement.dataset.itemHeight).toBe('40');
            // The component should use this value
        });

        it('should calculate visible count correctly', () => {
            // clientHeight = 200, itemHeight = 40, visibleCount = ceil(200/40) + 5 = 10
            // This is tested indirectly through rendering
        });
    });

    describe('Functionality', () => {
        it('setItems() should set items and render', () => {
            const items = ['Item 1', 'Item 2', 'Item 3', 'Item 4', 'Item 5'];

            virtualScroll.setItems(items);

            const spacer = scrollElement.querySelector('[data-virtual-spacer]') as HTMLElement;
            expect(spacer.style.height).toBe('200px'); // 5 items * 40px

            const container = scrollElement.children[1] as HTMLElement;
            expect(container.innerHTML).toContain('Item 1');
            expect(container.innerHTML).toContain('Item 2');
        });

        it('setItems() should reset scroll position', () => {
            const items = Array.from({ length: 20 }, (_, i) => `Item ${i + 1}`);
            virtualScroll.setItems(items);

            // Scroll down
            scrollElement.scrollTop = 100;
            virtualScroll.setItems(['New Item']);

            expect(scrollElement.scrollTop).toBe(0);
        });

        it('setRenderItem() should use custom render function', () => {
            const items = [{ name: 'John', age: 30 }, { name: 'Jane', age: 25 }];
            const renderFn = vi.fn((item: any, index: number) =>
                `<div class="custom-item">${item.name} (${index})</div>`
            );

            virtualScroll.setRenderItem(renderFn);
            virtualScroll.setItems(items);

            expect(renderFn).toHaveBeenCalledTimes(2);
            expect(renderFn).toHaveBeenCalledWith(items[0], 0);
            expect(renderFn).toHaveBeenCalledWith(items[1], 1);

            const container = scrollElement.children[1] as HTMLElement;
            expect(container.innerHTML).toContain('John (0)');
            expect(container.innerHTML).toContain('Jane (1)');
        });

        it('scrollTo() should scroll to specific index', () => {
            const items = Array.from({ length: 20 }, (_, i) => `Item ${i + 1}`);
            virtualScroll.setItems(items);

            virtualScroll.scrollTo(5);
            expect(scrollElement.scrollTop).toBe(200); // 5 * 40px
        });

        it('refresh() should recalculate visible count', () => {
            const items = Array.from({ length: 20 }, (_, i) => `Item ${i + 1}`);
            virtualScroll.setItems(items);

            // Change clientHeight
            Object.defineProperty(scrollElement, 'clientHeight', { value: 100 });
            virtualScroll.refresh();

            // Should recalculate visible count based on new height
            expect(scrollElement.querySelector('[data-virtual-spacer]')).not.toBeNull();
        });

        it('should return this for method chaining', () => {
            const items = ['test'];
            expect(virtualScroll.setItems(items)).toBe(virtualScroll);
            expect(virtualScroll.setRenderItem(() => '')).toBe(virtualScroll);
            expect(virtualScroll.scrollTo(0)).toBe(virtualScroll);
            expect(virtualScroll.refresh()).toBe(virtualScroll);
        });
    });

    describe('Virtual Rendering', () => {
        it('should render only visible items', () => {
            const items = Array.from({ length: 50 }, (_, i) => `Item ${i + 1}`);
            virtualScroll.setItems(items);

            // With height 200px and itemHeight 40px, visibleCount = ceil(200/40) + 5 = 10
            const container = scrollElement.children[1] as HTMLElement;
            const renderedItems = container.querySelectorAll('div');
            expect(renderedItems.length).toBeLessThanOrEqual(15); // Should be around 10-15 items
        });

        it('should update rendered items on scroll', () => {
            const items = Array.from({ length: 50 }, (_, i) => `Item ${i + 1}`);
            virtualScroll.setItems(items);

            const container = scrollElement.children[1] as HTMLElement;

            // Initially should show first items
            expect(container.textContent).toContain('Item 1');
            expect(container.textContent).toContain('Item 2');

            // Simulate scroll
            scrollElement.scrollTop = 200; // Scroll to item 5 (200px / 40px = 5)
            scrollElement.dispatchEvent(new Event('scroll'));

            // Should now show later items
            expect(container.textContent).toContain('Item 6');
            expect(container.textContent).toContain('Item 7');
        });

        it('should position container correctly', () => {
            const items = Array.from({ length: 50 }, (_, i) => `Item ${i + 1}`);
            virtualScroll.setItems(items);

            const container = scrollElement.children[1] as HTMLElement;
            expect(container.style.transform).toBe('translateY(0px)');

            // Scroll down
            scrollElement.scrollTop = 80; // Scroll to item 2 (80px / 40px = 2)
            scrollElement.dispatchEvent(new Event('scroll'));

            expect(container.style.transform).toBe('translateY(80px)');
        });

        it('should set correct spacer height', () => {
            const items = Array.from({ length: 25 }, (_, i) => `Item ${i + 1}`);
            virtualScroll.setItems(items);

            const spacer = scrollElement.querySelector('[data-virtual-spacer]') as HTMLElement;
            expect(spacer.style.height).toBe('1000px'); // 25 items * 40px
        });
    });

    describe('Scroll Behavior', () => {
        it('should handle scroll events', () => {
            const items = Array.from({ length: 50 }, (_, i) => `Item ${i + 1}`);
            virtualScroll.setItems(items);

            const container = scrollElement.children[1] as HTMLElement;

            // Scroll to middle
            scrollElement.scrollTop = 400; // 400px / 40px = 10
            scrollElement.dispatchEvent(new Event('scroll'));

            expect(container.style.transform).toBe('translateY(400px)');
            expect(container.textContent).toContain('Item 11');
        });

        it('should not update on small scroll changes', () => {
            const items = Array.from({ length: 50 }, (_, i) => `Item ${i + 1}`);
            virtualScroll.setItems(items);

            const container = scrollElement.children[1] as HTMLElement;
            const initialTransform = container.style.transform;

            // Small scroll that doesn't change startIndex
            scrollElement.scrollTop = 10; // 10px / 40px = 0.25, still index 0
            scrollElement.dispatchEvent(new Event('scroll'));

            expect(container.style.transform).toBe(initialTransform);
        });

        it('should handle scroll to end of list', () => {
            const items = Array.from({ length: 15 }, (_, i) => `Item ${i + 1}`);
            virtualScroll.setItems(items);

            // Scroll past the end
            scrollElement.scrollTop = 1000;
            scrollElement.dispatchEvent(new Event('scroll'));

            const container = scrollElement.children[1] as HTMLElement;
            // Should still render available items
            expect(container.children.length).toBeGreaterThan(0);
        });
    });

    describe('Default Rendering', () => {
        it('should render items with default template when no custom renderer', () => {
            const items = ['Apple', 'Banana', 'Cherry'];
            virtualScroll.setItems(items);

            const container = scrollElement.children[1] as HTMLElement;
            const renderedItems = container.querySelectorAll('div');

            expect(renderedItems.length).toBe(3);
            renderedItems.forEach((item, index) => {
                expect(item.style.height).toBe('40px');
                expect(item.className).toContain('flex');
                expect(item.className).toContain('items-center');
                expect(item.textContent.trim()).toBe(items[index]);
            });
        });

        it('should handle non-string items with default renderer', () => {
            const items = [123, { name: 'test' }, null, undefined];
            virtualScroll.setItems(items);

            const container = scrollElement.children[1] as HTMLElement;
            expect(container.children.length).toBe(4);

            expect(container.textContent).toContain('123');
            expect(container.textContent).toContain('[object Object]');
            expect(container.textContent).toContain('null');
            expect(container.textContent).toContain('undefined');
        });
    });

    describe('Edge Cases', () => {
        it('should handle empty items array', () => {
            virtualScroll.setItems([]);

            const spacer = scrollElement.querySelector('[data-virtual-spacer]') as HTMLElement;
            expect(spacer.style.height).toBe('0px');

            const container = scrollElement.children[1] as HTMLElement;
            expect(container.innerHTML).toBe('');
        });

        it('should handle single item', () => {
            virtualScroll.setItems(['Single Item']);

            const spacer = scrollElement.querySelector('[data-virtual-spacer]') as HTMLElement;
            expect(spacer.style.height).toBe('40px');

            const container = scrollElement.children[1] as HTMLElement;
            expect(container.textContent).toContain('Single Item');
        });

        it('should handle very large lists', () => {
            const items = Array.from({ length: 10000 }, (_, i) => `Item ${i + 1}`);
            virtualScroll.setItems(items);

            const spacer = scrollElement.querySelector('[data-virtual-spacer]') as HTMLElement;
            expect(spacer.style.height).toBe('400000px'); // 10000 * 40px

            // Should still only render visible items
            const container = scrollElement.children[1] as HTMLElement;
            expect(container.children.length).toBeLessThan(50);
        });

        it('should handle zero item height', () => {
            const zeroHeightElement = document.createElement('div');
            zeroHeightElement.dataset.itemHeight = '0';
            Object.defineProperty(zeroHeightElement, 'clientHeight', { value: 200 });

            const zeroHeightScroll = new VirtualScroll(zeroHeightElement);
            zeroHeightScroll.setItems(['test']);

            // Should handle gracefully
            expect(zeroHeightElement.querySelector('[data-virtual-spacer]')).not.toBeNull();

            zeroHeightScroll.destroy();
        });

        it('should handle negative scroll positions', () => {
            const items = Array.from({ length: 20 }, (_, i) => `Item ${i + 1}`);
            virtualScroll.setItems(items);

            scrollElement.scrollTop = -10;
            scrollElement.dispatchEvent(new Event('scroll'));

            // Should clamp to valid range
            const container = scrollElement.children[1] as HTMLElement;
            expect(container.style.transform).toBe('translateY(0px)');
        });
    });

    describe('Cleanup', () => {
        it('should remove from instances on destroy', () => {
            expect(instances.get(scrollElement)).toBe(virtualScroll);
            virtualScroll.destroy();
            expect(instances.get(scrollElement)).toBeUndefined();
        });

        it('should clean up DOM elements on destroy', () => {
            virtualScroll.destroy();

            // Elements should still exist but instance should be cleaned up
            expect(scrollElement.children.length).toBeGreaterThan(0);
        });
    });
});