import { describe, it, expect, beforeEach, vi } from 'vitest';
import { Accordion } from '../components/Accordion';

describe('Accordion Component', () => {
    let accordionEl: HTMLElement;
    let accordion: Accordion;

    beforeEach(() => {
        // Setup DOM structure
        accordionEl = document.createElement('div');
        accordionEl.setAttribute('data-accordion', '');
        accordionEl.innerHTML = `
      <div data-accordion-item="item1">
        <button data-accordion-trigger>Item 1</button>
        <div data-accordion-content class="hidden">Content 1</div>
      </div>
      <div data-accordion-item="item2">
        <button data-accordion-trigger>Item 2</button>
        <div data-accordion-content class="hidden">Content 2</div>
      </div>
      <div data-accordion-item="item3">
        <button data-accordion-trigger>Item 3</button>
        <div data-accordion-content class="hidden">Content 3</div>
      </div>
    `;
        document.body.appendChild(accordionEl);
        accordion = new Accordion(accordionEl);
    });

    afterEach(() => {
        document.body.removeChild(accordionEl);
    });

    describe('Initialization', () => {
        it('should initialize with default single mode', () => {
            const items = accordionEl.querySelectorAll('[data-accordion-item]');
            items.forEach((item, index) => {
                const trigger = item.querySelector('[data-accordion-trigger]');
                const content = item.querySelector('[data-accordion-content]');
                const expectedId = item.dataset.accordionItem || `accordion-${index}`;
                expect(trigger?.getAttribute('aria-expanded')).toBe('false');
                expect(trigger?.getAttribute('aria-controls')).toBe(`content-${expectedId}`);
                expect(content?.id).toBe(`content-${expectedId}`);
                expect(content?.getAttribute('aria-labelledby')).toBe(`trigger-${expectedId}`);
                expect(content?.getAttribute('role')).toBe('region');
            });
        });

        it('should initialize with multiple mode enabled', () => {
            accordionEl.setAttribute('data-multiple', 'true');
            const newAccordion = new Accordion(accordionEl);
            expect(newAccordion).toBeDefined();
        });

        it('should handle custom item IDs', () => {
            const trigger = accordionEl.querySelector('[data-accordion-trigger]');
            const content = accordionEl.querySelector('[data-accordion-content]');
            expect(trigger?.getAttribute('aria-controls')).toBe('content-item1');
            expect(content?.id).toBe('content-item1');
        });

        it('should handle missing trigger gracefully', () => {
            const item = accordionEl.querySelector('[data-accordion-item="item1"]');
            item?.querySelector('[data-accordion-trigger]')?.remove();
            const newAccordion = new Accordion(accordionEl);
            expect(newAccordion).toBeDefined();
        });

        it('should handle missing content gracefully', () => {
            const item = accordionEl.querySelector('[data-accordion-item="item1"]');
            item?.querySelector('[data-accordion-content]')?.remove();
            const newAccordion = new Accordion(accordionEl);
            expect(newAccordion).toBeDefined();
        });
    });

    describe('Toggle Functionality', () => {
        it('should toggle item open/close', () => {
            const trigger = accordionEl.querySelector('[data-accordion-trigger]');
            const content = accordionEl.querySelector('[data-accordion-content]');

            accordion.toggle('item1');
            expect(trigger?.getAttribute('aria-expanded')).toBe('true');
            expect(content?.classList.contains('hidden')).toBe(false);

            accordion.toggle('item1');
            expect(trigger?.getAttribute('aria-expanded')).toBe('false');
            expect(content?.classList.contains('hidden')).toBe(true);
        });

        it('should toggle first item when no itemId provided', () => {
            const trigger = accordionEl.querySelector('[data-accordion-trigger]');
            accordion.toggle();
            expect(trigger?.getAttribute('aria-expanded')).toBe('true');
        });

        it('should close other items in single mode', () => {
            // Set to single mode
            accordionEl.setAttribute('data-multiple', 'false');
            const singleAccordion = new Accordion(accordionEl);

            const triggers = accordionEl.querySelectorAll('[data-accordion-trigger]');
            const contents = accordionEl.querySelectorAll('[data-accordion-content]');

            singleAccordion.toggle('item1');
            expect(triggers[0]?.getAttribute('aria-expanded')).toBe('true');
            expect(contents[0]?.classList.contains('hidden')).toBe(false);

            singleAccordion.toggle('item2');

            expect(triggers[0]?.getAttribute('aria-expanded')).toBe('false');
            expect(contents[0]?.classList.contains('hidden')).toBe(true);
            expect(triggers[1]?.getAttribute('aria-expanded')).toBe('true');
            expect(contents[1]?.classList.contains('hidden')).toBe(false);
        });

        it('should allow multiple items open in multiple mode', () => {
            accordionEl.setAttribute('data-multiple', 'true');
            const newAccordion = new Accordion(accordionEl);

            const multiTriggers = accordionEl.querySelectorAll('[data-accordion-trigger]');
            const multiContents = accordionEl.querySelectorAll('[data-accordion-content]');

            newAccordion.toggle('item1');
            newAccordion.toggle('item2');

            expect(multiTriggers[0]?.getAttribute('aria-expanded')).toBe('true');
            expect(multiContents[0]?.classList.contains('hidden')).toBe(false);
            expect(multiTriggers[1]?.getAttribute('aria-expanded')).toBe('true');
            expect(multiContents[1]?.classList.contains('hidden')).toBe(false);
        });

        it('should handle invalid itemId gracefully', () => {
            accordion.toggle('invalid');
            expect(accordion).toBeDefined();
        });
    });

    describe('Open/Close Methods', () => {
        it('should open specific item', () => {
            const trigger = accordionEl.querySelector('[data-accordion-trigger]');
            const content = accordionEl.querySelector('[data-accordion-content]');

            accordion.open('item1');
            expect(trigger?.getAttribute('aria-expanded')).toBe('true');
            expect(content?.classList.contains('hidden')).toBe(false);
        });

        it('should close specific item', () => {
            accordion.open('item1');
            accordion.close('item1');

            const trigger = accordionEl.querySelector('[data-accordion-trigger]');
            const content = accordionEl.querySelector('[data-accordion-content]');
            expect(trigger?.getAttribute('aria-expanded')).toBe('false');
            expect(content?.classList.contains('hidden')).toBe(true);
        });

        it('should open all items', () => {
            accordionEl.setAttribute('data-multiple', 'true');
            const newAccordion = new Accordion(accordionEl);

            newAccordion.openAll();
            const triggers = accordionEl.querySelectorAll('[data-accordion-trigger]');
            const contents = accordionEl.querySelectorAll('[data-accordion-content]');

            triggers.forEach(trigger => {
                expect(trigger.getAttribute('aria-expanded')).toBe('true');
            });
            contents.forEach(content => {
                expect(content.classList.contains('hidden')).toBe(false);
            });
        });

        it('should close all items', () => {
            accordionEl.setAttribute('data-multiple', 'true');
            const newAccordion = new Accordion(accordionEl);

            newAccordion.openAll();
            newAccordion.closeAll();

            const triggers = accordionEl.querySelectorAll('[data-accordion-trigger]');
            const contents = accordionEl.querySelectorAll('[data-accordion-content]');

            triggers.forEach(trigger => {
                expect(trigger.getAttribute('aria-expanded')).toBe('false');
            });
            contents.forEach(content => {
                expect(content.classList.contains('hidden')).toBe(true);
            });
        });

        it('should closeAll work correctly', () => {
            // Open some items first
            accordion.toggle('item1');
            accordion.toggle('item2');

            // Now close all
            accordion.closeAll();

            const triggers = accordionEl.querySelectorAll('[data-accordion-trigger]');
            const contents = accordionEl.querySelectorAll('[data-accordion-content]');

            triggers.forEach(trigger => {
                expect(trigger.getAttribute('aria-expanded')).toBe('false');
            });
            contents.forEach(content => {
                expect(content.classList.contains('hidden')).toBe(true);
            });
        });
    });

    describe('Accessibility', () => {
        it('should set correct ARIA attributes', () => {
            const trigger = accordionEl.querySelector('[data-accordion-trigger]');
            const content = accordionEl.querySelector('[data-accordion-content]');

            expect(trigger?.getAttribute('aria-expanded')).toBe('false');
            expect(trigger?.getAttribute('aria-controls')).toBe('content-item1');
            expect(content?.getAttribute('aria-labelledby')).toBe('trigger-item1');
            expect(content?.getAttribute('role')).toBe('region');
        });

        it('should update aria-expanded when toggling', () => {
            const trigger = accordionEl.querySelector('[data-accordion-trigger]');
            accordion.toggle('item1');
            expect(trigger?.getAttribute('aria-expanded')).toBe('true');
            accordion.toggle('item1');
            expect(trigger?.getAttribute('aria-expanded')).toBe('false');
        });
    });

    describe('Events', () => {
        it('should emit accordion:toggled event', () => {
            const mockCallback = vi.fn();
            accordionEl.addEventListener('accordion:toggled', mockCallback);

            accordion.toggle('item1');
            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: { item: 'item1', open: true }
                })
            );
        });

        it('should emit accordion:opened event', () => {
            const mockCallback = vi.fn();
            accordionEl.addEventListener('accordion:opened', mockCallback);

            accordion.open('item1');
            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: { item: 'item1' }
                })
            );
        });

        it('should emit accordion:closed event', () => {
            const mockCallback = vi.fn();
            accordionEl.addEventListener('accordion:closed', mockCallback);

            accordion.open('item1');
            accordion.close('item1');
            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: { item: 'item1' }
                })
            );
        });
    });

    describe('Click Events', () => {
        it('should toggle on trigger click', () => {
            const trigger = accordionEl.querySelector('[data-accordion-trigger]');
            trigger?.click();

            expect(trigger?.getAttribute('aria-expanded')).toBe('true');
        });

        it('should handle multiple trigger clicks', () => {
            const trigger = accordionEl.querySelector('[data-accordion-trigger]');
            trigger?.click();
            expect(trigger?.getAttribute('aria-expanded')).toBe('true');

            trigger?.click();
            expect(trigger?.getAttribute('aria-expanded')).toBe('false');
        });
    });

    describe('Edge Cases', () => {
        it('should handle empty accordion', () => {
            const emptyEl = document.createElement('div');
            emptyEl.setAttribute('data-accordion', '');
            document.body.appendChild(emptyEl);

            const emptyAccordion = new Accordion(emptyEl);
            emptyAccordion.toggle();
            expect(emptyAccordion).toBeDefined();

            document.body.removeChild(emptyEl);
        });

        it('should handle items without data-accordion-item', () => {
            const item = accordionEl.querySelector('[data-accordion-item="item1"]');
            item?.removeAttribute('data-accordion-item');

            const newAccordion = new Accordion(accordionEl);
            expect(newAccordion).toBeDefined();
        });
    });

    describe('Cleanup', () => {
        it('should destroy without errors', () => {
            accordion.destroy();
            expect(accordion).toBeDefined();
        });
    });

    describe('Performance', () => {
        it('should perform toggle operations efficiently', () => {
            const startTime = performance.now();

            for (let i = 0; i < 100; i++) {
                accordion.toggle('item1');
                accordion.toggle('item2');
                accordion.toggle('item3');
            }

            const endTime = performance.now();
            const duration = endTime - startTime;
            expect(duration).toBeLessThan(1000); // Should complete in less than 1000ms
        });
    });
});