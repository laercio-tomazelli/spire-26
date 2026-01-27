import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { Dropdown } from '../components/Dropdown';
import { emit } from '../core/registry';

describe('Dropdown Component', () => {
    let container: HTMLElement;
    let dropdownInstance: Dropdown;

    beforeEach(() => {
        container = document.createElement('div');
        container.innerHTML = `
      <div data-dropdown>
        <button data-trigger>Toggle Dropdown</button>
        <div data-menu class="hidden">
          <a href="#" data-item>Item 1</a>
          <a href="#" data-item>Item 2</a>
          <a href="#" data-item>Item 3</a>
        </div>
      </div>
    `;
        document.body.appendChild(container);

        dropdownInstance = new Dropdown(container.querySelector('[data-dropdown]')!);
    });

    afterEach(() => {
        dropdownInstance.destroy();
        document.body.removeChild(container);
    });

    describe('Initialization', () => {
        it('should initialize with correct structure', () => {
            expect(dropdownInstance).toBeDefined();
            const trigger = container.querySelector('[data-trigger]');
            const menu = container.querySelector('[data-menu]');
            expect(trigger).toBeTruthy();
            expect(menu).toBeTruthy();
        });

        it('should set up accessibility attributes', () => {
            const trigger = container.querySelector('[data-trigger]');
            const menu = container.querySelector('[data-menu]');

            expect(trigger?.getAttribute('aria-haspopup')).toBe('true');
            expect(trigger?.getAttribute('aria-expanded')).toBe('false');
            expect(menu?.getAttribute('role')).toBe('menu');
        });

        it('should start closed', () => {
            const menu = container.querySelector('[data-menu]');
            expect(menu?.classList.contains('hidden')).toBe(true);
        });
    });

    describe('Toggle Functionality', () => {
        it('should open dropdown', () => {
            dropdownInstance.open();

            const trigger = container.querySelector('[data-trigger]');
            const menu = container.querySelector('[data-menu]');

            expect(menu?.classList.contains('hidden')).toBe(false);
            expect(trigger?.getAttribute('aria-expanded')).toBe('true');
        });

        it('should close dropdown', () => {
            dropdownInstance.open();
            dropdownInstance.close();

            const trigger = container.querySelector('[data-trigger]');
            const menu = container.querySelector('[data-menu]');

            expect(menu?.classList.contains('hidden')).toBe(true);
            expect(trigger?.getAttribute('aria-expanded')).toBe('false');
        });

        it('should toggle dropdown', () => {
            const menu = container.querySelector('[data-menu]');

            dropdownInstance.toggle();
            expect(menu?.classList.contains('hidden')).toBe(false);

            dropdownInstance.toggle();
            expect(menu?.classList.contains('hidden')).toBe(true);
        });
    });

    describe('Event Handling', () => {
        it('should handle trigger click', () => {
            const trigger = container.querySelector('[data-trigger]');
            const menu = container.querySelector('[data-menu]');

            trigger?.click();
            expect(menu?.classList.contains('hidden')).toBe(false);

            trigger?.click();
            expect(menu?.classList.contains('hidden')).toBe(true);
        });

        it('should close on outside click', () => {
            dropdownInstance.open();
            const menu = container.querySelector('[data-menu]');
            expect(menu?.classList.contains('hidden')).toBe(false);

            // Click outside
            document.body.click();
            expect(menu?.classList.contains('hidden')).toBe(true);
        });

        it('should close on escape key', () => {
            dropdownInstance.open();
            const menu = container.querySelector('[data-menu]');
            const trigger = container.querySelector('[data-trigger]');
            expect(menu?.classList.contains('hidden')).toBe(false);

            // Press escape
            const escapeEvent = new KeyboardEvent('keydown', { key: 'Escape' });
            trigger?.dispatchEvent(escapeEvent);

            expect(menu?.classList.contains('hidden')).toBe(true);
        });

        it('should focus trigger after escape', () => {
            dropdownInstance.open();
            const trigger = container.querySelector('[data-trigger]');

            const escapeEvent = new KeyboardEvent('keydown', { key: 'Escape' });
            trigger?.dispatchEvent(escapeEvent);

            expect(document.activeElement).toBe(trigger);
        });
    });

    describe('Performance', () => {
        it('should handle rapid toggling efficiently', () => {
            const start = performance.now();

            for (let i = 0; i < 100; i++) {
                dropdownInstance.toggle();
            }

            const end = performance.now();
            const duration = end - start;

            expect(duration).toBeLessThan(200); // Should complete 100 toggles in <200ms
        });
    });

    describe('Edge Cases', () => {
        it('should handle missing menu gracefully', () => {
            const emptyContainer = document.createElement('div');
            emptyContainer.innerHTML = '<button data-trigger>Trigger</button>';
            document.body.appendChild(emptyContainer);

            const emptyDropdown = new Dropdown(emptyContainer);
            expect(() => emptyDropdown.toggle()).not.toThrow();

            document.body.removeChild(emptyContainer);
        });

        it('should handle missing trigger gracefully', () => {
            const emptyContainer = document.createElement('div');
            emptyContainer.innerHTML = '<div data-menu class="hidden">Menu</div>';
            document.body.appendChild(emptyContainer);

            const emptyDropdown = new Dropdown(emptyContainer);
            expect(() => emptyDropdown.toggle()).not.toThrow();

            document.body.removeChild(emptyContainer);
        });
    });
});