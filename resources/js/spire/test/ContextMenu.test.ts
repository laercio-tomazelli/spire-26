import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { ContextMenu } from '../components/ContextMenu';

describe('ContextMenu Component', () => {
    let targetEl: HTMLElement;
    let contextMenu: ContextMenu;
    let mockHandler: vi.MockedFunction<any>;

    beforeEach(() => {
        vi.useFakeTimers();
        mockHandler = vi.fn();

        // Setup target element with context menu items
        targetEl = document.createElement('div');
        targetEl.setAttribute('data-context-items', JSON.stringify([
            { id: 'item1', label: 'First Item', handler: mockHandler },
            { id: 'item2', label: 'Second Item', shortcut: 'Ctrl+C', handler: mockHandler },
            { id: 'divider', divider: true },
            { id: 'item3', label: 'Third Item', icon: '🔍', disabled: true, handler: mockHandler },
            { id: 'item4', label: 'Danger Item', danger: true, handler: mockHandler }
        ]));
        document.body.appendChild(targetEl);

        contextMenu = new ContextMenu(targetEl);
    });

    afterEach(() => {
        contextMenu.destroy();
        document.body.removeChild(targetEl);
        vi.restoreAllMocks();
    });

    describe('Initialization', () => {
        it('should initialize with target element', () => {
            expect(contextMenu).toBeDefined();
            expect(contextMenu.isOpen()).toBe(false);
        });

        it('should parse items from data attribute', () => {
            const simpleEl = document.createElement('div');
            simpleEl.setAttribute('data-context-items', JSON.stringify([
                { id: 'test', label: 'Test Item', handler: mockHandler }
            ]));
            document.body.appendChild(simpleEl);

            const simpleMenu = new ContextMenu(simpleEl);
            expect(simpleMenu).toBeDefined();

            simpleMenu.destroy();
            document.body.removeChild(simpleEl);
        });

        it('should handle invalid JSON gracefully', () => {
            const invalidEl = document.createElement('div');
            invalidEl.setAttribute('data-context-items', 'invalid-json');
            document.body.appendChild(invalidEl);

            const invalidMenu = new ContextMenu(invalidEl);
            expect(invalidMenu).toBeDefined();

            invalidMenu.destroy();
            document.body.removeChild(invalidEl);
        });

        it('should handle missing data attribute', () => {
            const emptyEl = document.createElement('div');
            document.body.appendChild(emptyEl);

            const emptyMenu = new ContextMenu(emptyEl);
            expect(emptyMenu).toBeDefined();

            emptyMenu.destroy();
            document.body.removeChild(emptyEl);
        });
    });

    describe('Menu Display', () => {
        it('should show menu on right click', () => {
            const event = new MouseEvent('contextmenu', { clientX: 100, clientY: 100 });
            targetEl.dispatchEvent(event);

            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            expect(menuEl).toBeTruthy();
            expect(contextMenu.isOpen()).toBe(true);
        });

        it('should show menu programmatically', () => {
            contextMenu.show(100, 100);

            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            expect(menuEl).toBeTruthy();
            expect(contextMenu.isOpen()).toBe(true);
        });

        it('should show menu with custom items', () => {
            const customItems = [
                { id: 'custom', label: 'Custom Item', handler: mockHandler }
            ];

            contextMenu.show(100, 100, customItems);

            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            expect(menuEl).toBeTruthy();
        });

        it('should not show menu with empty items', () => {
            const emptyMenu = new ContextMenu(document.createElement('div'));
            emptyMenu.show(100, 100, []);

            expect(emptyMenu.isOpen()).toBe(false);
        });

        it('should hide menu', () => {
            contextMenu.show(100, 100);
            contextMenu.hide();
            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            expect(menuEl).toBeFalsy();
            expect(contextMenu.isOpen()).toBe(false);
        });
    });

    describe('Menu Items', () => {
        beforeEach(() => {
            contextMenu.show(100, 100);
        });

        afterEach(() => {
            contextMenu.hide();
        });

        it('should render regular items', () => {
            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            const items = menuEl?.querySelectorAll('[data-context-item]');
            expect(items?.length).toBe(4); // 4 selectable items
        });

        it('should render dividers', () => {
            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            const dividers = menuEl?.querySelectorAll('.border-t');
            expect(dividers?.length).toBe(1);
        });

        it('should render disabled items', () => {
            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            const disabledItem = menuEl?.querySelector('[data-item-id="item3"]');
            expect(disabledItem?.hasAttribute('disabled')).toBe(true);
            expect(disabledItem?.classList.contains('opacity-50')).toBe(true);
        });

        it('should render danger items', () => {
            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            const dangerItem = menuEl?.querySelector('[data-item-id="item4"]');
            expect(dangerItem?.classList.contains('text-red-600')).toBe(true);
        });

        it('should render items with icons', () => {
            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            const iconItem = menuEl?.querySelector('[data-item-id="item3"] .w-5');
            expect(iconItem?.textContent).toBe('🔍');
        });

        it('should render items with shortcuts', () => {
            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            const shortcutItem = menuEl?.querySelector('[data-item-id="item2"]');
            expect(shortcutItem?.textContent).toContain('Ctrl+C');
        });
    });

    describe('Dynamic Items', () => {
        it('should set items programmatically', () => {
            const newItems = [
                { id: 'new1', label: 'New Item 1', handler: mockHandler },
                { id: 'new2', label: 'New Item 2', handler: mockHandler }
            ];

            contextMenu.setItems(newItems);
            contextMenu.show(100, 100);

            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            const items = menuEl?.querySelectorAll('[data-context-item]');
            expect(items?.length).toBe(2);

            contextMenu.hide();
        });
    });

    describe('Events', () => {
        it('should emit contextmenu:opened event', () => {
            const mockCallback = vi.fn();
            targetEl.addEventListener('contextmenu:opened', mockCallback);

            contextMenu.show(100, 100);

            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: {}
                })
            );
        });

        it('should emit contextmenu:closed event', () => {
            const mockCallback = vi.fn();
            targetEl.addEventListener('contextmenu:closed', mockCallback);

            contextMenu.show(100, 100);
            contextMenu.hide();

            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: {}
                })
            );
        });
    });

    describe('Edge Cases', () => {
        it('should handle empty labels', () => {
            const emptyLabelItems = [
                { id: 'empty', label: '', handler: mockHandler }
            ];

            contextMenu.setItems(emptyLabelItems);
            contextMenu.show(100, 100);

            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            const item = menuEl?.querySelector('[data-item-id="empty"]');
            expect(item?.textContent?.trim()).toBe('');

            contextMenu.hide();
        });

        it('should handle multiple consecutive dividers', () => {
            const multiDividerItems = [
                { id: 'item1', label: 'Item 1', handler: mockHandler },
                { divider: true },
                { divider: true },
                { id: 'item2', label: 'Item 2', handler: mockHandler }
            ];

            contextMenu.setItems(multiDividerItems);
            contextMenu.show(100, 100);

            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            const dividers = menuEl?.querySelectorAll('.border-t');
            expect(dividers?.length).toBe(2);

            contextMenu.hide();
        });

        it('should handle all items disabled', () => {
            const allDisabledItems = [
                { id: 'disabled1', label: 'Disabled 1', disabled: true, handler: mockHandler },
                { id: 'disabled2', label: 'Disabled 2', disabled: true, handler: mockHandler }
            ];

            contextMenu.setItems(allDisabledItems);
            contextMenu.show(100, 100);

            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            const items = menuEl?.querySelectorAll('[data-context-item]');
            expect(items?.length).toBe(2);

            contextMenu.hide();
        });
    });

    describe('Cleanup', () => {
        it('should destroy without errors', () => {
            expect(() => contextMenu.destroy()).not.toThrow();
        });

        it('should hide menu on destroy', () => {
            contextMenu.show(100, 100);
            contextMenu.destroy();

            expect(document.querySelector('.fixed.z-\\[9999\\]')).toBeFalsy();
        });
    });

    describe('Performance', () => {
        it('should handle large item lists efficiently', () => {
            const largeItems = [];
            for (let i = 0; i < 100; i++) {
                largeItems.push({
                    id: `item${i}`,
                    label: `Item ${i}`,
                    handler: mockHandler
                });
            }

            const startTime = performance.now();
            contextMenu.setItems(largeItems);
            contextMenu.show(100, 100);

            const menuEl = document.querySelector('.fixed.z-\\[9999\\]');
            const items = menuEl?.querySelectorAll('[data-context-item]');
            expect(items?.length).toBe(100);

            const endTime = performance.now();
            const duration = endTime - startTime;
            expect(duration).toBeLessThan(1000); // Should render in less than 1 second

            contextMenu.hide();
        });
    });
});