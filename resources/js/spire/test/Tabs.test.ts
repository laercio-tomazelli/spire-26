import { describe, it, expect, beforeEach, afterEach } from 'vitest';
import { Tabs } from '../components/Tabs';

describe('Tabs Component', () => {
    let container: HTMLElement;
    let tabsInstance: Tabs;

    beforeEach(() => {
        container = document.createElement('div');
        container.setAttribute('data-v', 'tabs');
        container.innerHTML = `
      <div role="tablist" class="flex border-b">
        <button data-tab="tab1" class="px-4 py-3 text-sm font-medium border-b-2 -mb-px transition-colors border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 [&.active]:border-blue-600 [&.active]:text-blue-600 dark:[&.active]:border-blue-400 dark:[&.active]:text-blue-400">Tab 1</button>
        <button data-tab="tab2" class="px-4 py-3 text-sm font-medium border-b-2 -mb-px transition-colors border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 [&.active]:border-blue-600 [&.active]:text-blue-600 dark:[&.active]:border-blue-400 dark:[&.active]:text-blue-400">Tab 2</button>
        <button data-tab="tab3" class="px-4 py-3 text-sm font-medium border-b-2 -mb-px transition-colors border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 [&.active]:border-blue-600 [&.active]:text-blue-600 dark:[&.active]:border-blue-400 dark:[&.active]:text-blue-400">Tab 3</button>
      </div>
      <div class="mt-4">
        <div data-panel="tab1">Content 1</div>
        <div data-panel="tab2" class="hidden">Content 2</div>
        <div data-panel="tab3" class="hidden">Content 3</div>
      </div>
    `;
        document.body.appendChild(container);

        tabsInstance = new Tabs(container);
    });

    afterEach(() => {
        document.body.removeChild(container);
    });

    describe('Initialization', () => {
        it('should initialize with correct structure', () => {
            expect(tabsInstance).toBeDefined();
            const tablist = container.querySelector('[role="tablist"]');
            const panels = container.querySelectorAll('[data-panel]');
            expect(tablist).toBeTruthy();
            expect(panels.length).toBe(3);
        });

        it('should set up accessibility attributes', () => {
            const tabs = container.querySelectorAll('[data-tab]');
            tabs.forEach((tab, index) => {
                expect(tab.getAttribute('role')).toBe('tab');
                expect(tab.getAttribute('aria-selected')).toBe(index === 0 ? 'true' : 'false');
                expect(tab.getAttribute('tabindex')).toBe(index === 0 ? '0' : '-1');
            });
        });

        it('should show first tab by default', () => {
            const tab1 = container.querySelector('[data-tab="tab1"]');
            const panel1 = container.querySelector('[data-panel="tab1"]');
            expect(tab1?.classList.contains('active')).toBe(true);
            expect(panel1?.classList.contains('hidden')).toBe(false);
        });
    });

    describe('Navigation', () => {
        it('should show specified tab and hide others', () => {
            // Before show
            const panel1Before = container.querySelector('[data-panel="tab1"]');
            const panel2Before = container.querySelector('[data-panel="tab2"]');
            expect(panel1Before?.classList.contains('hidden')).toBe(false);
            expect(panel2Before?.classList.contains('hidden')).toBe(true);

            // Show tab2
            tabsInstance.show('tab2');

            const tab1 = container.querySelector('[data-tab="tab1"]');
            const tab2 = container.querySelector('[data-tab="tab2"]');
            const panel1 = container.querySelector('[data-panel="tab1"]');
            const panel2 = container.querySelector('[data-panel="tab2"]');

            expect(tab1?.classList.contains('active')).toBe(false);
            expect(tab2?.classList.contains('active')).toBe(true);
            expect(panel1?.classList.contains('hidden')).toBe(true);
            expect(panel2?.classList.contains('hidden')).toBe(false);
        });

        it('should return current active tab', () => {
            expect(tabsInstance.current()).toBe('tab1');
            tabsInstance.show('tab3');
            expect(tabsInstance.current()).toBe('tab3');
        });

        it('should handle invalid tab gracefully', () => {
            expect(() => {
                tabsInstance.show('invalid-tab');
            }).not.toThrow();
        });

        it('should perform tab switching within performance limits', () => {
            const start = performance.now();
            for (let i = 0; i < 50; i++) {
                tabsInstance.show('tab1');
                tabsInstance.show('tab2');
                tabsInstance.show('tab3');
            }
            const end = performance.now();
            const duration = end - start;

            expect(duration).toBeLessThan(1000); // Allow more time for CI environments
        });
    });

    describe('State Management', () => {
        it('should disable and enable tabs', () => {
            tabsInstance.disable('tab2');

            const tab2 = container.querySelector('[data-tab="tab2"]');
            expect(tab2?.hasAttribute('disabled')).toBe(true);
            expect(tab2?.classList.contains('disabled')).toBe(true);
            expect(tab2?.getAttribute('aria-disabled')).toBe('true');

            tabsInstance.enable('tab2');
            expect(tab2?.hasAttribute('disabled')).toBe(false);
            expect(tab2?.classList.contains('disabled')).toBe(false);
            expect(tab2?.getAttribute('aria-disabled')).toBe('false');
        });

        it('should switch to next available tab when current is disabled', () => {
            tabsInstance.show('tab2');
            expect(tabsInstance.current()).toBe('tab2');

            tabsInstance.disable('tab2');
            expect(tabsInstance.current()).toBe('tab1');
        });

        it('should hide and unhide tabs', () => {
            tabsInstance.hide('tab2');

            const tab2 = container.querySelector('[data-tab="tab2"]');
            const panel2 = container.querySelector('[data-panel="tab2"]');
            expect(tab2?.classList.contains('tab-hidden')).toBe(true);
            expect(panel2?.classList.contains('hidden')).toBe(true);

            tabsInstance.unhide('tab2');
            expect(tab2?.classList.contains('tab-hidden')).toBe(false);
        });

        it('should switch to next available tab when current is hidden', () => {
            tabsInstance.show('tab2');
            expect(tabsInstance.current()).toBe('tab2');

            tabsInstance.hide('tab2');
            expect(tabsInstance.current()).toBe('tab1');
        });
    });

    describe('Dynamic Operations', () => {
        it('should add new tab', () => {
            tabsInstance.add({
                name: 'tab4',
                label: 'Tab 4',
                content: 'Content 4'
            });

            const tab4 = container.querySelector('[data-tab="tab4"]');
            const panel4 = container.querySelector('[data-panel="tab4"]');
            expect(tab4).toBeTruthy();
            expect(panel4).toBeTruthy();
            expect(panel4?.innerHTML).toBe('Content 4');
        });

        it('should add tab at specific position', () => {
            tabsInstance.add({
                name: 'tab4',
                label: 'Tab 4',
                content: 'Content 4',
                position: 1
            });

            const tabs = container.querySelectorAll('[data-tab]');
            expect(tabs[1]?.dataset.tab).toBe('tab4');
        });

        it('should activate tab when added with active flag', () => {
            tabsInstance.add({
                name: 'tab4',
                label: 'Tab 4',
                content: 'Content 4',
                active: true
            });

            expect(tabsInstance.current()).toBe('tab4');
        });

        it('should remove tab', () => {
            tabsInstance.remove('tab2');

            const tab2 = container.querySelector('[data-tab="tab2"]');
            const panel2 = container.querySelector('[data-panel="tab2"]');
            expect(tab2).toBeFalsy();
            expect(panel2).toBeFalsy();
        });

        it('should switch to next tab when current is removed', () => {
            tabsInstance.show('tab2');
            expect(tabsInstance.current()).toBe('tab2');

            tabsInstance.remove('tab2');
            expect(tabsInstance.current()).toBe('tab1');
        });
    });

    describe('Highlighting', () => {
        it('should highlight tab with error type', () => {
            tabsInstance.highlight('tab1', { type: 'error' });

            const tab1 = container.querySelector('[data-tab="tab1"]');
            expect(tab1?.classList.contains('text-red-600')).toBe(true);
            expect(tab1?.classList.contains('bg-red-100')).toBe(true);
            expect(tab1?.dataset.highlightType).toBe('error');
        });

        it('should highlight with pulse animation', () => {
            tabsInstance.highlight('tab1', { type: 'warning', pulse: true });

            const tab1 = container.querySelector('[data-tab="tab1"]');
            expect(tab1?.classList.contains('animate-pulse')).toBe(true);
            expect(tab1?.dataset.highlightPulse).toBe('true');
        });

        it('should add badge to highlighted tab', () => {
            tabsInstance.highlight('tab1', { type: 'success', badge: 5 });

            const badge = container.querySelector('[data-tab="tab1"] [data-tab-badge]');
            expect(badge).toBeTruthy();
            expect(badge?.textContent).toBe('5');
        });

        it('should clear highlight', () => {
            tabsInstance.highlight('tab1', { type: 'error', pulse: true, badge: '!' });
            tabsInstance.clearHighlight('tab1');

            const tab1 = container.querySelector('[data-tab="tab1"]');
            expect(tab1?.classList.contains('text-red-600')).toBe(false);
            expect(tab1?.classList.contains('animate-pulse')).toBe(false);
            expect(tab1?.querySelector('[data-tab-badge]')).toBeFalsy();
        });

        it('should clear all highlights', () => {
            tabsInstance.highlight('tab1', { type: 'error' });
            tabsInstance.highlight('tab2', { type: 'warning' });
            tabsInstance.clearAllHighlights();

            const tabs = container.querySelectorAll('[data-tab]');
            tabs.forEach(tab => {
                expect(tab.classList.contains('tab-highlighted')).toBe(false);
            });
        });
    });

    describe('List and Events', () => {
        it('should list visible tabs', () => {
            const list = tabsInstance.list();
            expect(list).toHaveLength(3);
            expect(list[0]).toEqual({ name: 'tab1', label: 'Tab' });
        });

        it('should exclude hidden tabs from list', () => {
            tabsInstance.hide('tab2');
            const list = tabsInstance.list();
            expect(list).toHaveLength(2);
            expect(list.some(tab => tab.name === 'tab2')).toBe(false);
        });
    });

    describe('Edge Cases', () => {
        it('should handle empty container gracefully', () => {
            const emptyContainer = document.createElement('div');
            document.body.appendChild(emptyContainer);

            const emptyTabs = new Tabs(emptyContainer);
            expect(emptyTabs.current()).toBeNull();
            expect(emptyTabs.list()).toHaveLength(0);

            document.body.removeChild(emptyContainer);
        });

        it('should handle tabs without panels', () => {
            const noPanelContainer = document.createElement('div');
            noPanelContainer.innerHTML = `
        <div role="tablist">
          <button data-tab="tab1">Tab 1</button>
        </div>
      `;
            document.body.appendChild(noPanelContainer);

            const noPanelTabs = new Tabs(noPanelContainer);
            noPanelTabs.show('tab1');

            document.body.removeChild(noPanelContainer);
        });
    });
});