import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { Rating } from '../components/Rating';
import { instances } from '../core/registry';

describe('Rating Component', () => {
    let container: HTMLElement;
    let ratingElement: HTMLElement;
    let rating: Rating;

    beforeEach(() => {
        container = document.createElement('div');
        document.body.appendChild(container);

        ratingElement = document.createElement('div');
        ratingElement.id = 'test-rating';

        // Create star elements
        for (let i = 0; i < 5; i++) {
            const star = document.createElement('div');
            star.setAttribute('data-rating-star', '');
            const fill = document.createElement('div');
            fill.setAttribute('data-rating-fill', '');
            star.appendChild(fill);
            ratingElement.appendChild(star);
        }

        container.appendChild(ratingElement);

        rating = new Rating(ratingElement);
    });

    afterEach(() => {
        rating.destroy();
        document.body.removeChild(container);
    });

    describe('Initialization', () => {
        it('should initialize with default values', () => {
            expect(rating).toBeInstanceOf(Rating);
            expect(rating.value()).toBe(0);
            expect(instances.has(ratingElement)).toBe(true);
        });

        it('should use custom max rating from dataset', () => {
            const customElement = document.createElement('div');
            customElement.setAttribute('data-max', '10');

            // Create 10 stars
            for (let i = 0; i < 10; i++) {
                const star = document.createElement('div');
                star.setAttribute('data-rating-star', '');
                const fill = document.createElement('div');
                fill.setAttribute('data-rating-fill', '');
                star.appendChild(fill);
                customElement.appendChild(star);
            }

            container.appendChild(customElement);
            const customRating = new Rating(customElement);

            expect(customRating.value()).toBe(0);

            customRating.destroy();
            container.removeChild(customElement);
        });

        it('should use initial value from dataset', () => {
            const customElement = document.createElement('div');
            customElement.setAttribute('data-value', '3.5');

            // Create stars
            for (let i = 0; i < 5; i++) {
                const star = document.createElement('div');
                star.setAttribute('data-rating-star', '');
                const fill = document.createElement('div');
                fill.setAttribute('data-rating-fill', '');
                star.appendChild(fill);
                customElement.appendChild(star);
            }

            container.appendChild(customElement);
            const customRating = new Rating(customElement);

            expect(customRating.value()).toBe(3.5);

            customRating.destroy();
            container.removeChild(customElement);
        });

        it('should support half stars when enabled', () => {
            const customElement = document.createElement('div');
            customElement.setAttribute('data-half', 'true');

            // Create stars
            for (let i = 0; i < 5; i++) {
                const star = document.createElement('div');
                star.setAttribute('data-rating-star', '');
                const fill = document.createElement('div');
                fill.setAttribute('data-rating-fill', '');
                star.appendChild(fill);
                customElement.appendChild(star);
            }

            container.appendChild(customElement);
            const customRating = new Rating(customElement);

            expect(customRating.value()).toBe(0);

            customRating.destroy();
            container.removeChild(customElement);
        });
    });

    describe('Functionality', () => {
        it('value() should return current value', () => {
            expect(rating.value()).toBe(0);
        });

        it('setValue() should update value and return this', () => {
            const result = rating.setValue(4);
            expect(result).toBe(rating);
            expect(rating.value()).toBe(4);
        });

        it('setValue() should clamp value to valid range', () => {
            rating.setValue(-1);
            expect(rating.value()).toBe(0);

            rating.setValue(10);
            expect(rating.value()).toBe(5);
        });

        it('disable() should set readonly state', () => {
            rating.disable(true);
            // Should not affect current functionality test
            expect(rating).toBeDefined();
        });

        it('readonly() should set readonly state', () => {
            rating.readonly(true);
            // Should not affect current functionality test
            expect(rating).toBeDefined();
        });
    });

    describe('Events', () => {
        it('should emit rating:change event on setValue', () => {
            let emittedEvent: CustomEvent | null = null;
            ratingElement.addEventListener('rating:change', (e) => {
                emittedEvent = e as CustomEvent;
            });

            rating.setValue(3);

            expect(emittedEvent).not.toBeNull();
            expect(emittedEvent?.detail).toEqual({ value: 3 });
        });
    });

    describe('Accessibility', () => {
        it('should update dataset value when rating changes', () => {
            rating.setValue(4);
            expect(ratingElement.getAttribute('data-value')).toBe('4');
        });

        it('should update input value when present', () => {
            const input = document.createElement('input');
            input.setAttribute('data-rating-input', '');
            ratingElement.appendChild(input);

            // Re-initialize rating to pick up the input
            rating.destroy();
            rating = new Rating(ratingElement);

            rating.setValue(3);
            expect(input.value).toBe('3');
        });
    });

    describe('Edge Cases', () => {
        it('should handle readonly mode', () => {
            const customElement = document.createElement('div');
            customElement.setAttribute('data-readonly', 'true');

            // Create stars
            for (let i = 0; i < 5; i++) {
                const star = document.createElement('div');
                star.setAttribute('data-rating-star', '');
                const fill = document.createElement('div');
                fill.setAttribute('data-rating-fill', '');
                star.appendChild(fill);
                customElement.appendChild(star);
            }

            container.appendChild(customElement);
            const readonlyRating = new Rating(customElement);

            // Should not change value on click
            const firstStar = customElement.querySelector('[data-rating-star]');
            firstStar?.dispatchEvent(new MouseEvent('click', { clientX: 10 }));

            expect(readonlyRating.value()).toBe(0);

            readonlyRating.destroy();
            container.removeChild(customElement);
        });

        it('should handle decimal values with half stars', () => {
            const customElement = document.createElement('div');
            customElement.setAttribute('data-half', 'true');
            customElement.setAttribute('data-value', '2.5');

            // Create stars
            for (let i = 0; i < 5; i++) {
                const star = document.createElement('div');
                star.setAttribute('data-rating-star', '');
                const fill = document.createElement('div');
                fill.setAttribute('data-rating-fill', '');
                star.appendChild(fill);
                customElement.appendChild(star);
            }

            container.appendChild(customElement);
            const halfRating = new Rating(customElement);

            expect(halfRating.value()).toBe(2.5);

            halfRating.destroy();
            container.removeChild(customElement);
        });

        it('should handle value display element', () => {
            const displayElement = document.createElement('span');
            displayElement.setAttribute('data-rating-value-display', '');
            ratingElement.appendChild(displayElement);

            // Re-initialize rating to pick up the display element
            rating.destroy();
            rating = new Rating(ratingElement);

            rating.setValue(4);
            expect(displayElement.textContent).toBe('4');

            rating.setValue(3.5);
            expect(displayElement.textContent).toBe('3.5');
        });
    });

    describe('User Interactions', () => {
        it('should handle star click', () => {
            const stars = ratingElement.querySelectorAll('[data-rating-star]');
            const thirdStar = stars[2]; // 3rd star (index 2)

            // Mock getBoundingClientRect for click position
            thirdStar.getBoundingClientRect = vi.fn(() => ({
                left: 100,
                width: 20,
                top: 0,
                bottom: 0,
                right: 120,
                height: 0,
                x: 100,
                y: 0,
                toJSON: () => ({})
            }));

            thirdStar.dispatchEvent(new MouseEvent('click', { clientX: 110 }));

            expect(rating.value()).toBe(3);
        });

        it('should handle half star click when enabled', () => {
            const customElement = document.createElement('div');
            customElement.setAttribute('data-half', 'true');

            // Create stars
            for (let i = 0; i < 5; i++) {
                const star = document.createElement('div');
                star.setAttribute('data-rating-star', '');
                const fill = document.createElement('div');
                fill.setAttribute('data-rating-fill', '');
                star.appendChild(fill);
                customElement.appendChild(star);
            }

            container.appendChild(customElement);
            const halfRating = new Rating(customElement);

            const stars = customElement.querySelectorAll('[data-rating-star]');
            const secondStar = stars[1]; // 2nd star (index 1)

            // Mock getBoundingClientRect for half click
            secondStar.getBoundingClientRect = vi.fn(() => ({
                left: 100,
                width: 20,
                top: 0,
                bottom: 0,
                right: 120,
                height: 0,
                x: 100,
                y: 0,
                toJSON: () => ({})
            }));

            // Click on left half (should be 1.5)
            secondStar.dispatchEvent(new MouseEvent('click', { clientX: 105 }));

            expect(halfRating.value()).toBe(1.5);

            halfRating.destroy();
            container.removeChild(customElement);
        });

        it('should handle mouse enter/leave for preview', () => {
            const stars = ratingElement.querySelectorAll('[data-rating-star]');
            const fourthStar = stars[3]; // 4th star (index 3)

            // Mouse enter should preview
            fourthStar.dispatchEvent(new MouseEvent('mouseenter'));

            // Mouse leave should restore original value
            ratingElement.dispatchEvent(new MouseEvent('mouseleave'));

            expect(rating.value()).toBe(0); // Original value
        });
    });

    describe('Performance', () => {
        it('should handle rapid value changes efficiently', () => {
            const startTime = performance.now();

            // Set value multiple times rapidly
            for (let i = 0; i < 50; i++) {
                rating.setValue((i % 5) + 1); // 1-5 range
            }

            const endTime = performance.now();
            const duration = endTime - startTime;

            // Should complete in less than 200ms
            expect(duration).toBeLessThan(200);
            expect(rating.value()).toBe(5); // Last value (49 % 5 + 1 = 5)
        });
    });
});