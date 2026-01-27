import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { Carousel } from '../components/Carousel';

describe('Carousel Component', () => {
    let carouselEl: HTMLElement;
    let carousel: Carousel;

    beforeEach(() => {
        // Setup DOM structure
        carouselEl = document.createElement('div');
        carouselEl.setAttribute('data-carousel', '');
        carouselEl.innerHTML = `
      <div data-carousel-track>
        <div data-carousel-slide>Slide 1</div>
        <div data-carousel-slide>Slide 2</div>
        <div data-carousel-slide>Slide 3</div>
      </div>
      <button data-carousel-prev>Prev</button>
      <button data-carousel-next>Next</button>
      <div data-carousel-indicator data-slide="0"></div>
      <div data-carousel-indicator data-slide="1"></div>
      <div data-carousel-indicator data-slide="2"></div>
    `;
        document.body.appendChild(carouselEl);
        carousel = new Carousel(carouselEl);
    });

    afterEach(() => {
        document.body.removeChild(carouselEl);
    });

    describe('Initialization', () => {
        it('should initialize with default settings', () => {
            expect(carousel.current()).toBe(0);
            const slides = carouselEl.querySelectorAll('[data-carousel-slide]');
            expect(slides[0].classList.contains('opacity-100')).toBe(true);
            expect(slides[1].classList.contains('opacity-0')).toBe(true);
        });

        it('should initialize with autoplay enabled', () => {
            carouselEl.setAttribute('data-autoplay', 'true');
            const autoplayCarousel = new Carousel(carouselEl);
            expect(carouselEl.dataset.playing).toBe('true');
        });

        it('should initialize with custom interval', () => {
            carouselEl.setAttribute('data-autoplay', 'true');
            carouselEl.setAttribute('data-interval', '3000');
            const customCarousel = new Carousel(carouselEl);
            expect(customCarousel).toBeDefined();
        });

        it('should initialize without loop', () => {
            carouselEl.setAttribute('data-loop', 'false');
            const noLoopCarousel = new Carousel(carouselEl);
            expect(noLoopCarousel).toBeDefined();
        });

        it('should handle missing elements gracefully', () => {
            const minimalEl = document.createElement('div');
            minimalEl.setAttribute('data-carousel', '');
            minimalEl.innerHTML = '<div data-carousel-track><div data-carousel-slide>Slide 1</div></div>';
            document.body.appendChild(minimalEl);

            const minimalCarousel = new Carousel(minimalEl);
            expect(minimalCarousel.current()).toBe(0);

            document.body.removeChild(minimalEl);
        });
    });

    describe('Navigation', () => {
        it('should navigate to next slide', () => {
            carousel.next();
            expect(carousel.current()).toBe(1);
            const slides = carouselEl.querySelectorAll('[data-carousel-slide]');
            expect(slides[1].classList.contains('opacity-100')).toBe(true);
            expect(slides[0].classList.contains('opacity-0')).toBe(true);
        });

        it('should navigate to previous slide', () => {
            carousel.next(); // Go to slide 1
            carousel.prev(); // Back to slide 0
            expect(carousel.current()).toBe(0);
        });

        it('should loop to first slide when reaching the end', () => {
            carousel.goto(2); // Go to last slide
            carousel.next(); // Should loop to first
            expect(carousel.current()).toBe(0);
        });

        it('should loop to last slide when going before first', () => {
            carousel.prev(); // Should loop to last slide
            expect(carousel.current()).toBe(2);
        });

        it('should not loop when loop is disabled', () => {
            carouselEl.setAttribute('data-loop', 'false');
            const noLoopCarousel = new Carousel(carouselEl);

            noLoopCarousel.goto(2); // Go to last
            noLoopCarousel.next(); // Should stay at last
            expect(noLoopCarousel.current()).toBe(2);

            noLoopCarousel.goto(0); // Go to first
            noLoopCarousel.prev(); // Should stay at first
            expect(noLoopCarousel.current()).toBe(0);
        });

        it('should go to specific slide', () => {
            carousel.goto(2);
            expect(carousel.current()).toBe(2);
        });

        it('should ignore invalid goto indices', () => {
            carousel.goto(10);
            expect(carousel.current()).toBe(0);

            carousel.goto(-1);
            expect(carousel.current()).toBe(0);
        });
    });

    describe('UI Updates', () => {
        it('should update slide visibility', () => {
            carousel.next();
            const slides = carouselEl.querySelectorAll('[data-carousel-slide]');
            expect(slides[0].getAttribute('aria-hidden')).toBe('true');
            expect(slides[1].getAttribute('aria-hidden')).toBe('false');
            expect(slides[0].classList.contains('z-0')).toBe(true);
            expect(slides[1].classList.contains('z-10')).toBe(true);
        });

        it('should update indicators', () => {
            carousel.next();
            const indicators = carouselEl.querySelectorAll('[data-carousel-indicator]');
            expect(indicators[0].classList.contains('bg-white/50')).toBe(true);
            expect(indicators[1].classList.contains('bg-white')).toBe(true);
            expect(indicators[0].getAttribute('aria-current')).toBe('false');
            expect(indicators[1].getAttribute('aria-current')).toBe('true');
        });

        it('should disable navigation buttons at boundaries when loop is disabled', () => {
            carouselEl.setAttribute('data-loop', 'false');
            const noLoopCarousel = new Carousel(carouselEl);

            const prevBtn = carouselEl.querySelector('[data-carousel-prev]');
            const nextBtn = carouselEl.querySelector('[data-carousel-next]');

            expect(prevBtn?.classList.contains('opacity-50')).toBe(true);
            expect(prevBtn?.classList.contains('cursor-not-allowed')).toBe(true);

            noLoopCarousel.goto(2);
            expect(nextBtn?.classList.contains('opacity-50')).toBe(true);
            expect(nextBtn?.classList.contains('cursor-not-allowed')).toBe(true);
        });
    });

    describe('Autoplay', () => {
        it('should start autoplay when enabled', () => {
            carouselEl.setAttribute('data-autoplay', 'true');
            const autoplayCarousel = new Carousel(carouselEl);
            expect(carouselEl.dataset.playing).toBe('true');
        });

        it('should pause autoplay', () => {
            carouselEl.setAttribute('data-autoplay', 'true');
            const autoplayCarousel = new Carousel(carouselEl);
            autoplayCarousel.pause();
            expect(carouselEl.dataset.playing).toBe('false');
        });

        it('should resume autoplay', () => {
            carouselEl.setAttribute('data-autoplay', 'true');
            const autoplayCarousel = new Carousel(carouselEl);
            autoplayCarousel.pause();
            autoplayCarousel.play();
            expect(carouselEl.dataset.playing).toBe('true');
        });

        it('should pause on hover when enabled', () => {
            carouselEl.setAttribute('data-autoplay', 'true');
            carouselEl.setAttribute('data-pause-on-hover', 'true');
            const hoverCarousel = new Carousel(carouselEl);

            carouselEl.dispatchEvent(new Event('mouseenter'));
            expect(carouselEl.dataset.playing).toBe('false');

            carouselEl.dispatchEvent(new Event('mouseleave'));
            expect(carouselEl.dataset.playing).toBe('true');
        });
    });

    describe('Events', () => {
        it('should emit carousel:change event', () => {
            const mockCallback = vi.fn();
            carouselEl.addEventListener('carousel:change', mockCallback);

            carousel.next();
            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: { index: 1, total: 3 }
                })
            );
        });

        it('should emit carousel:play event', () => {
            const mockCallback = vi.fn();
            carouselEl.addEventListener('carousel:play', mockCallback);

            carousel.play();
            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: {}
                })
            );
        });

        it('should emit carousel:pause event', () => {
            carouselEl.setAttribute('data-autoplay', 'true');
            const autoplayCarousel = new Carousel(carouselEl);

            const mockCallback = vi.fn();
            carouselEl.addEventListener('carousel:pause', mockCallback);

            autoplayCarousel.pause();
            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: {}
                })
            );
        });
    });

    describe('Click Events', () => {
        it('should navigate on prev button click', () => {
            carousel.next(); // Go to slide 1
            const prevBtn = carouselEl.querySelector('[data-carousel-prev]');
            prevBtn?.click();
            expect(carousel.current()).toBe(0);
        });

        it('should navigate on next button click', () => {
            const nextBtn = carouselEl.querySelector('[data-carousel-next]');
            nextBtn?.click();
            expect(carousel.current()).toBe(1);
        });

        it('should navigate on indicator click', () => {
            const indicators = carouselEl.querySelectorAll('[data-carousel-indicator]');
            indicators[2]?.click();
            expect(carousel.current()).toBe(2);
        });
    });

    describe('Keyboard Events', () => {
        it('should navigate with arrow keys', () => {
            carouselEl.dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowRight' }));
            expect(carousel.current()).toBe(1);

            carouselEl.dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowLeft' }));
            expect(carousel.current()).toBe(0);
        });
    });

    describe('Touch Events', () => {
        it('should handle swipe left (next)', () => {
            const mockTouch = {
                identifier: 0,
                target: carouselEl,
                screenX: 100
            };

            // Simulate touch start
            const touchStartEvent = new TouchEvent('touchstart', {
                changedTouches: [mockTouch as any]
            });
            carouselEl.dispatchEvent(touchStartEvent);

            // Simulate touch end (swipe left - start 100, end 40, diff = 60 > 50)
            const touchEndEvent = new TouchEvent('touchend', {
                changedTouches: [{ ...mockTouch, screenX: 40 } as any]
            });
            carouselEl.dispatchEvent(touchEndEvent);

            expect(carousel.current()).toBe(1);
        });

        it('should handle swipe right (prev)', () => {
            carousel.next(); // Go to slide 1

            const mockTouch = {
                identifier: 0,
                target: carouselEl,
                screenX: 40
            };

            // Simulate touch start
            const touchStartEvent = new TouchEvent('touchstart', {
                changedTouches: [mockTouch as any]
            });
            carouselEl.dispatchEvent(touchStartEvent);

            // Simulate touch end (swipe right - start 40, end 100, diff = -60 < 0)
            const touchEndEvent = new TouchEvent('touchend', {
                changedTouches: [{ ...mockTouch, screenX: 100 } as any]
            });
            carouselEl.dispatchEvent(touchEndEvent);

            expect(carousel.current()).toBe(0);
        });

        it('should pause autoplay on touch start', () => {
            carouselEl.setAttribute('data-autoplay', 'true');
            const autoplayCarousel = new Carousel(carouselEl);

            const touchStartEvent = new TouchEvent('touchstart', {
                changedTouches: [{
                    identifier: 0,
                    target: carouselEl,
                    screenX: 100
                } as any]
            });
            carouselEl.dispatchEvent(touchStartEvent);

            expect(carouselEl.dataset.playing).toBe('false');
        });
    });

    describe('Edge Cases', () => {
        it('should handle single slide', () => {
            const singleEl = document.createElement('div');
            singleEl.setAttribute('data-carousel', '');
            singleEl.innerHTML = '<div data-carousel-track><div data-carousel-slide>Single Slide</div></div>';
            document.body.appendChild(singleEl);

            const singleCarousel = new Carousel(singleEl);
            singleCarousel.next();
            expect(singleCarousel.current()).toBe(0); // Should stay at 0

            document.body.removeChild(singleEl);
        });

        it('should handle empty carousel', () => {
            const emptyEl = document.createElement('div');
            emptyEl.setAttribute('data-carousel', '');
            emptyEl.innerHTML = '<div data-carousel-track></div>';
            document.body.appendChild(emptyEl);

            const emptyCarousel = new Carousel(emptyEl);
            expect(emptyCarousel.current()).toBe(0);

            document.body.removeChild(emptyEl);
        });
    });

    describe('Cleanup', () => {
        it('should destroy without errors', () => {
            carousel.destroy();
            expect(carousel).toBeDefined();
        });

        it('should clear autoplay timer on destroy', () => {
            carouselEl.setAttribute('data-autoplay', 'true');
            const autoplayCarousel = new Carousel(carouselEl);
            autoplayCarousel.destroy();
            expect(carouselEl.dataset.playing).toBe('false');
        });
    });

    describe('Performance', () => {
        it('should perform navigation operations efficiently', () => {
            const startTime = performance.now();

            for (let i = 0; i < 100; i++) {
                carousel.next();
                carousel.prev();
                carousel.goto(0);
            }

            const endTime = performance.now();
            const duration = endTime - startTime;
            expect(duration).toBeLessThan(500); // Should complete in less than 500ms
        });
    });
});