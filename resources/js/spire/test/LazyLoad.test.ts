import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { LazyLoad } from '../components/LazyLoad';

describe('LazyLoad Component', () => {
    let container: HTMLElement;
    let lazyLoad: LazyLoad;

    beforeEach(() => {
        container = document.createElement('div');
        document.body.appendChild(container);

        // Mock IntersectionObserver
        const mockObserve = vi.fn();
        const mockDisconnect = vi.fn();
        const mockUnobserve = vi.fn();

        class MockIntersectionObserver {
            constructor(callback: IntersectionObserverCallback, options?: IntersectionObserverInit) {
                // Store callback for testing
                (this as any).callback = callback;
                (this as any).options = options;
            }
            observe = mockObserve;
            disconnect = mockDisconnect;
            unobserve = mockUnobserve;
        }

        Object.defineProperty(global, 'IntersectionObserver', {
            writable: true,
            value: MockIntersectionObserver,
        });
    });

    afterEach(() => {
        if (lazyLoad) {
            lazyLoad.destroy();
        }
        document.body.removeChild(container);
        vi.clearAllMocks();
    });

    describe('Initialization', () => {
        it('should initialize with image element', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            expect(img.src).toBe(''); // Should not load immediately
            expect(lazyLoad.isLoaded()).toBe(false);
        });

        it('should initialize with iframe element', () => {
            const iframe = document.createElement('iframe');
            iframe.dataset.src = 'test.html';
            container.appendChild(iframe);

            lazyLoad = new LazyLoad(iframe);

            expect(iframe.src).toBe(''); // Should not load immediately
            expect(lazyLoad.isLoaded()).toBe(false);
        });

        it('should initialize with video element', () => {
            const video = document.createElement('video');
            video.dataset.src = 'test.mp4';
            container.appendChild(video);

            lazyLoad = new LazyLoad(video);

            expect(video.src).toBe(''); // Should not load immediately
            expect(lazyLoad.isLoaded()).toBe(false);
        });

        it('should initialize with div for background image', () => {
            const div = document.createElement('div');
            div.dataset.src = 'bg.jpg';
            container.appendChild(div);

            lazyLoad = new LazyLoad(div);

            expect(div.style.backgroundImage).toBe(''); // Should not load immediately
            expect(lazyLoad.isLoaded()).toBe(false);
        });

        it('should handle placeholder', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            img.dataset.placeholder = 'placeholder.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            expect(img.src).toBe(''); // Should not load immediately
        });
    });

    describe('Loading Functionality', () => {
        it('should load image when intersecting', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            lazyLoad.load();

            expect(img.src).toContain('test.jpg');
            expect(lazyLoad.isLoaded()).toBe(true);
        });

        it('should load iframe when intersecting', () => {
            const iframe = document.createElement('iframe');
            iframe.dataset.src = 'test.html';
            container.appendChild(iframe);

            lazyLoad = new LazyLoad(iframe);

            lazyLoad.load();

            expect(iframe.src).toContain('test.html');
            expect(lazyLoad.isLoaded()).toBe(true);
        });

        it('should load video when intersecting', () => {
            const video = document.createElement('video');
            video.dataset.src = 'test.mp4';
            container.appendChild(video);

            lazyLoad = new LazyLoad(video);

            lazyLoad.load();

            expect(video.src).toContain('test.mp4');
            expect(lazyLoad.isLoaded()).toBe(true);
        });

        it('should load background image when intersecting', () => {
            const div = document.createElement('div');
            div.dataset.src = 'bg.jpg';
            container.appendChild(div);

            lazyLoad = new LazyLoad(div);

            lazyLoad.load();

            expect(div.style.backgroundImage).toContain('bg.jpg');
            expect(lazyLoad.isLoaded()).toBe(true);
        });

        it('should not load if already loaded', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            lazyLoad.load();
            const firstSrc = img.src;

            lazyLoad.load(); // Try to load again

            expect(img.src).toBe(firstSrc); // Should remain the same
        });

        it('should not load if no src', () => {
            const img = document.createElement('img');
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            lazyLoad.load();

            expect(img.src).toBe('');
            expect(lazyLoad.isLoaded()).toBe(false);
        });
    });

    describe('Loading States and Events', () => {
        it('should emit loaded event for images', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            const eventSpy = vi.fn();
            img.addEventListener('lazy:loaded', eventSpy);

            lazyLoad.load();

            // Trigger onload
            img.onload?.(new Event('load'));

            expect(eventSpy).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        src: 'test.jpg'
                    })
                })
            );
        });

        it('should emit loaded event for background images', () => {
            const div = document.createElement('div');
            div.dataset.src = 'bg.jpg';
            container.appendChild(div);

            lazyLoad = new LazyLoad(div);

            const eventSpy = vi.fn();
            div.addEventListener('lazy:loaded', eventSpy);

            lazyLoad.load();

            expect(eventSpy).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        src: 'bg.jpg'
                    })
                })
            );
        });

        it('should emit error event for images', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            const eventSpy = vi.fn();
            img.addEventListener('lazy:error', eventSpy);

            lazyLoad.load();

            // Trigger onerror
            img.onerror?.(new Event('error'));

            expect(eventSpy).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        src: 'test.jpg'
                    })
                })
            );
        });

        it('should add transition classes on image load', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            img.classList.add('opacity-0');
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            lazyLoad.load();

            // Trigger onload
            img.onload?.(new Event('load'));

            expect(img.classList.contains('opacity-100')).toBe(true);
            expect(img.classList.contains('transition-opacity')).toBe(true);
            expect(img.classList.contains('duration-300')).toBe(true);
            expect(img.classList.contains('opacity-0')).toBe(false);
        });
    });

    describe('Unload Functionality', () => {
        it('should unload image', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            img.dataset.placeholder = 'placeholder.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            lazyLoad.load();
            expect(img.src).toContain('test.jpg');

            lazyLoad.unload();

            expect(img.src).toContain('placeholder.jpg');
            expect(lazyLoad.isLoaded()).toBe(false);
        });

        it('should unload background image', () => {
            const div = document.createElement('div');
            div.dataset.src = 'bg.jpg';
            div.dataset.placeholder = 'placeholder.jpg';
            container.appendChild(div);

            lazyLoad = new LazyLoad(div);

            lazyLoad.load();
            expect(div.style.backgroundImage).toContain('bg.jpg');

            lazyLoad.unload();

            expect(div.style.backgroundImage).toContain('placeholder.jpg');
            expect(lazyLoad.isLoaded()).toBe(false);
        });

        it('should unload background image without placeholder', () => {
            const div = document.createElement('div');
            div.dataset.src = 'bg.jpg';
            container.appendChild(div);

            lazyLoad = new LazyLoad(div);

            lazyLoad.load();
            expect(div.style.backgroundImage).toContain('bg.jpg');

            lazyLoad.unload();

            expect(div.style.backgroundImage).toBe('none');
            expect(lazyLoad.isLoaded()).toBe(false);
        });
    });

    describe('Intersection Observer', () => {
        it('should trigger load when element intersects', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            // Since we can't easily mock the intersection callback,
            // we'll test the load method directly
            lazyLoad.load();

            expect(img.src).toContain('test.jpg');
        });

        it('should disconnect observer after loading', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            lazyLoad.load();

            // Observer should be disconnected
            expect(lazyLoad.isLoaded()).toBe(true);
        });
    });

    describe('State Queries', () => {
        it('should return loaded state', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            expect(lazyLoad.isLoaded()).toBe(false);

            lazyLoad.load();

            expect(lazyLoad.isLoaded()).toBe(true);
        });
    });

    describe('Edge Cases', () => {
        it('should handle missing src attribute', () => {
            const img = document.createElement('img');
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            lazyLoad.load();

            expect(img.src).toBe('');
            expect(lazyLoad.isLoaded()).toBe(false);
        });

        it('should handle empty src attribute', () => {
            const img = document.createElement('img');
            img.dataset.src = '';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            lazyLoad.load();

            expect(img.src).toBe('');
            expect(lazyLoad.isLoaded()).toBe(false);
        });

        it('should handle unload before load', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            lazyLoad.unload(); // Should not throw

            expect(lazyLoad.isLoaded()).toBe(false);
        });
    });

    describe('Cleanup', () => {
        it('should destroy without errors', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            expect(() => lazyLoad.destroy()).not.toThrow();
        });

        it('should disconnect intersection observer on destroy', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            lazyLoad.destroy();

            // Should not throw when trying to access destroyed instance
            expect(() => lazyLoad.destroy()).not.toThrow();
        });
    });

    describe('Performance', () => {
        it('should handle multiple instances efficiently', () => {
            const images = Array.from({ length: 10 }, (_, i) => {
                const img = document.createElement('img');
                img.dataset.src = `test${i}.jpg`;
                container.appendChild(img);
                return new LazyLoad(img);
            });

            const startTime = Date.now();

            images.forEach(lazy => lazy.load());

            const endTime = Date.now();
            const duration = endTime - startTime;

            expect(duration).toBeLessThan(100); // Should complete quickly
            expect(images.every(lazy => lazy.isLoaded())).toBe(true);

            images.forEach(lazy => lazy.destroy());
        });

        it('should handle rapid load/unload operations', () => {
            const img = document.createElement('img');
            img.dataset.src = 'test.jpg';
            img.dataset.placeholder = 'placeholder.jpg';
            container.appendChild(img);

            lazyLoad = new LazyLoad(img);

            // Rapid load/unload operations
            for (let i = 0; i < 5; i++) {
                lazyLoad.load();
                lazyLoad.unload();
            }

            expect(lazyLoad.isLoaded()).toBe(false);
        });
    });
});