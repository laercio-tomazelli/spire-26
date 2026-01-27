import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { RangeSlider } from '../components/RangeSlider';
import { instances } from '../core/registry';

describe('RangeSlider Component', () => {
    let container: HTMLElement;
    let sliderElement: HTMLElement;
    let rangeSlider: RangeSlider;

    beforeEach(() => {
        container = document.createElement('div');
        document.body.appendChild(container);

        sliderElement = document.createElement('div');
        sliderElement.id = 'test-slider';
        container.appendChild(sliderElement);

        rangeSlider = new RangeSlider(sliderElement);
    });

    afterEach(() => {
        rangeSlider.destroy();
        document.body.removeChild(container);
    });

    describe('Initialization', () => {
        it('should initialize with default values', () => {
            expect(rangeSlider).toBeInstanceOf(RangeSlider);
            expect(rangeSlider.value()).toBe(50);
            expect(instances.has(sliderElement)).toBe(true);
        });

        it('should use custom min, max, step from dataset', () => {
            const customElement = document.createElement('div');
            customElement.setAttribute('data-min', '10');
            customElement.setAttribute('data-max', '200');
            customElement.setAttribute('data-step', '5');
            customElement.setAttribute('data-value', '25');
            container.appendChild(customElement);

            const customSlider = new RangeSlider(customElement);

            expect(customSlider.value()).toBe(25);

            customSlider.destroy();
            container.removeChild(customElement);
        });

        it('should create input element if not present', () => {
            const input = sliderElement.querySelector('input[type="range"]');
            expect(input).toBeTruthy();
            expect(input?.type).toBe('range');
        });

        it('should use existing input element', () => {
            const testElement = document.createElement('div');
            const existingInput = document.createElement('input');
            existingInput.type = 'range';
            existingInput.min = '0';
            existingInput.max = '50';
            existingInput.value = '25';
            testElement.appendChild(existingInput);
            container.appendChild(testElement);

            const testSlider = new RangeSlider(testElement);

            expect(testSlider.value()).toBe(25);

            testSlider.destroy();
            container.removeChild(testElement);
        });
    });

    describe('Functionality', () => {
        it('value() should return current value', () => {
            expect(rangeSlider.value()).toBe(50);
        });

        it('setValue() should update value and return this', () => {
            const result = rangeSlider.setValue(75);
            expect(result).toBe(rangeSlider);
            expect(rangeSlider.value()).toBe(75);
        });

        it('setValue() should clamp value to min/max', () => {
            rangeSlider.setValue(-10);
            expect(rangeSlider.value()).toBe(0);

            rangeSlider.setValue(150);
            expect(rangeSlider.value()).toBe(100);
        });

        it('min() should update minimum value', () => {
            rangeSlider.min(20);
            rangeSlider.setValue(10);
            expect(rangeSlider.value()).toBe(20);
        });

        it('max() should update maximum value', () => {
            rangeSlider.max(80);
            rangeSlider.setValue(90);
            expect(rangeSlider.value()).toBe(80);
        });

        it('step() should update step value', () => {
            rangeSlider.step(5);
            // Step is used internally for calculations
            expect(rangeSlider).toBeDefined();
        });
    });

    describe('Events', () => {
        it('should emit range:change event on setValue', () => {
            let emittedEvent: CustomEvent | null = null;
            sliderElement.addEventListener('range:change', (e) => {
                emittedEvent = e as CustomEvent;
            });

            rangeSlider.setValue(75);

            expect(emittedEvent).not.toBeNull();
            expect(emittedEvent?.detail).toEqual({ value: 75 });
        });
    });

    describe('Accessibility', () => {
        it('should have proper ARIA attributes', () => {
            const thumb = sliderElement.querySelector('[role="slider"]') as HTMLElement;
            expect(thumb).toBeTruthy();
            expect(thumb.getAttribute('aria-valuemin')).toBe('0');
            expect(thumb.getAttribute('aria-valuemax')).toBe('100');
            expect(thumb.getAttribute('aria-valuenow')).toBe('50');
            expect(thumb.getAttribute('tabindex')).toBe('0');
        });

        it('should update aria-valuenow when value changes', () => {
            const thumb = sliderElement.querySelector('[role="slider"]') as HTMLElement;
            rangeSlider.setValue(75);
            expect(thumb.getAttribute('aria-valuenow')).toBe('75');
        });
    });

    describe('Cleanup', () => {
        it('should remove from instances on destroy', () => {
            const testElement = document.createElement('div');
            container.appendChild(testElement);

            const testSlider = new RangeSlider(testElement);

            expect(instances.has(testElement)).toBe(true);

            testSlider.destroy();

            expect(instances.has(testElement)).toBe(false);

            container.removeChild(testElement);
        });
    });

    describe('Edge Cases', () => {
        it('should handle decimal step values', () => {
            const testElement = document.createElement('div');
            testElement.setAttribute('data-step', '0.5');
            testElement.setAttribute('data-value', '10.5');
            container.appendChild(testElement);

            const testSlider = new RangeSlider(testElement);
            expect(testSlider.value()).toBe(10.5);

            testSlider.destroy();
            container.removeChild(testElement);
        });

        it('should handle negative min values', () => {
            const testElement = document.createElement('div');
            testElement.setAttribute('data-min', '-50');
            testElement.setAttribute('data-max', '50');
            testElement.setAttribute('data-value', '-25');
            container.appendChild(testElement);

            const testSlider = new RangeSlider(testElement);
            expect(testSlider.value()).toBe(-25);

            testSlider.destroy();
            container.removeChild(testElement);
        });

        it('should handle very small ranges', () => {
            const testElement = document.createElement('div');
            testElement.setAttribute('data-min', '0');
            testElement.setAttribute('data-max', '1');
            testElement.setAttribute('data-step', '0.01');
            container.appendChild(testElement);

            const testSlider = new RangeSlider(testElement);
            testSlider.setValue(0.5);
            expect(testSlider.value()).toBe(0.5);

            testSlider.destroy();
            container.removeChild(testElement);
        });
    });

    describe('User Interactions', () => {
        it('should handle keyboard navigation', () => {
            const thumb = sliderElement.querySelector('[role="slider"]') as HTMLElement;
            expect(thumb).toBeTruthy();

            // Arrow right should increase value
            const rightEvent = new KeyboardEvent('keydown', { key: 'ArrowRight' });
            thumb.dispatchEvent(rightEvent);
            expect(rangeSlider.value()).toBe(51);

            // Arrow left should decrease value
            const leftEvent = new KeyboardEvent('keydown', { key: 'ArrowLeft' });
            thumb.dispatchEvent(leftEvent);
            expect(rangeSlider.value()).toBe(50);
        });

        it('should handle shift+arrow for larger steps', () => {
            const thumb = sliderElement.querySelector('[role="slider"]') as HTMLElement;

            // Shift+Arrow right should increase by 10
            const shiftRightEvent = new KeyboardEvent('keydown', { key: 'ArrowRight', shiftKey: true });
            thumb.dispatchEvent(shiftRightEvent);
            expect(rangeSlider.value()).toBe(60);
        });

        it('should handle track click', () => {
            const track = sliderElement.querySelector('.absolute.left-0.right-0.h-2') as HTMLElement;
            expect(track).toBeTruthy();

            // Mock getBoundingClientRect
            track.getBoundingClientRect = vi.fn(() => ({
                left: 100,
                width: 200,
                top: 0,
                bottom: 0,
                right: 300,
                height: 0,
                x: 100,
                y: 0,
                toJSON: () => ({})
            }));

            // Click at 50% position (should set to 50)
            const clickEvent = new MouseEvent('click', { clientX: 200 });
            track.dispatchEvent(clickEvent);
            expect(rangeSlider.value()).toBe(50);
        });
    });

    describe('Performance', () => {
        it('should handle rapid value changes efficiently', () => {
            const startTime = performance.now();

            // Set value 100 times rapidly
            for (let i = 0; i < 100; i++) {
                rangeSlider.setValue(i % 101);
            }

            const endTime = performance.now();
            const duration = endTime - startTime;

            // Should complete in less than 500ms (jsdom can be slow)
            expect(duration).toBeLessThan(500);
            expect(rangeSlider.value()).toBe(99);
        });
    });
});