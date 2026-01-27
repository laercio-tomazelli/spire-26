import { describe, it, expect, beforeEach, afterEach } from 'vitest';
import { Skeleton } from '../components/Skeleton';
import { instances } from '../core/registry';

describe('Skeleton Component', () => {
    let container: HTMLElement;
    let skeletonElement: HTMLElement;
    let targetElement: HTMLElement;
    let skeleton: Skeleton;

    beforeEach(() => {
        container = document.createElement('div');
        document.body.appendChild(container);

        skeletonElement = document.createElement('div');
        skeletonElement.id = 'test-skeleton';
        skeletonElement.className = 'skeleton-loader';
        skeletonElement.dataset.target = '#target-content';
        container.appendChild(skeletonElement);

        targetElement = document.createElement('div');
        targetElement.id = 'target-content';
        targetElement.textContent = 'Real content';
        container.appendChild(targetElement);

        skeleton = new Skeleton(skeletonElement);
    });

    afterEach(() => {
        skeleton.destroy();
        document.body.removeChild(container);
    });

    describe('Initialization', () => {
        it('should initialize with skeleton element', () => {
            expect(skeleton).toBeInstanceOf(Skeleton);
            expect(instances.has(skeletonElement)).toBe(true);
        });

        it('should find target element by selector', () => {
            const customSkeleton = document.createElement('div');
            customSkeleton.setAttribute('data-target', '#target-content');
            container.appendChild(customSkeleton);

            const customSkeletonInstance = new Skeleton(customSkeleton);
            expect(customSkeletonInstance).toBeInstanceOf(Skeleton);

            customSkeletonInstance.destroy();
            container.removeChild(customSkeleton);
        });

        it('should handle missing target element gracefully', () => {
            const customSkeleton = document.createElement('div');
            customSkeleton.setAttribute('data-target', '#non-existent');
            container.appendChild(customSkeleton);

            const customSkeletonInstance = new Skeleton(customSkeleton);
            expect(customSkeletonInstance).toBeInstanceOf(Skeleton);

            customSkeletonInstance.destroy();
            container.removeChild(customSkeleton);
        });
    });

    describe('Functionality', () => {
        it('show() should show skeleton and hide target', () => {
            skeletonElement.classList.add('hidden');
            targetElement.classList.remove('hidden');

            skeleton.show();

            expect(skeletonElement.classList.contains('hidden')).toBe(false);
            expect(targetElement.classList.contains('hidden')).toBe(true);
        });

        it('hide() should hide skeleton and show target', () => {
            skeletonElement.classList.remove('hidden');
            targetElement.classList.add('hidden');

            skeleton.hide();

            expect(skeletonElement.classList.contains('hidden')).toBe(true);
            expect(targetElement.classList.contains('hidden')).toBe(false);
        });

        it('toggle() should toggle visibility', () => {
            // Initially hidden
            skeletonElement.classList.add('hidden');
            targetElement.classList.remove('hidden');

            skeleton.toggle();
            expect(skeletonElement.classList.contains('hidden')).toBe(false);
            expect(targetElement.classList.contains('hidden')).toBe(true);

            skeleton.toggle();
            expect(skeletonElement.classList.contains('hidden')).toBe(true);
            expect(targetElement.classList.contains('hidden')).toBe(false);
        });

        it('should return this for method chaining', () => {
            expect(skeleton.show()).toBe(skeleton);
            expect(skeleton.hide()).toBe(skeleton);
            expect(skeleton.toggle()).toBe(skeleton);
        });
    });

    describe('Events', () => {
        it('should emit skeleton:shown event on show', () => {
            let emittedEvent: CustomEvent | null = null;
            skeletonElement.addEventListener('skeleton:shown', (e) => {
                emittedEvent = e as CustomEvent;
            });

            skeleton.show();

            expect(emittedEvent).not.toBeNull();
            expect(emittedEvent?.detail).toEqual({});
        });

        it('should emit skeleton:hidden event on hide', () => {
            let emittedEvent: CustomEvent | null = null;
            skeletonElement.addEventListener('skeleton:hidden', (e) => {
                emittedEvent = e as CustomEvent;
            });

            skeleton.hide();

            expect(emittedEvent).not.toBeNull();
            expect(emittedEvent?.detail).toEqual({});
        });
    });

    describe('Target Element Handling', () => {
        it('should work without target element', () => {
            const standaloneSkeleton = document.createElement('div');
            container.appendChild(standaloneSkeleton);

            const standaloneInstance = new Skeleton(standaloneSkeleton);

            standaloneInstance.show();
            expect(standaloneSkeleton.classList.contains('hidden')).toBe(false);

            standaloneInstance.hide();
            expect(standaloneSkeleton.classList.contains('hidden')).toBe(true);

            standaloneInstance.destroy();
            container.removeChild(standaloneSkeleton);
        });

        it('should handle target element visibility correctly', () => {
            // Set up target relationship
            skeletonElement.setAttribute('data-target', '#target-content');

            // Re-initialize to pick up target
            skeleton.destroy();
            skeleton = new Skeleton(skeletonElement);

            // Initially both visible
            skeletonElement.classList.remove('hidden');
            targetElement.classList.remove('hidden');

            skeleton.show();
            expect(skeletonElement.classList.contains('hidden')).toBe(false);
            expect(targetElement.classList.contains('hidden')).toBe(true);

            skeleton.hide();
            expect(skeletonElement.classList.contains('hidden')).toBe(true);
            expect(targetElement.classList.contains('hidden')).toBe(false);
        });
    });

    describe('Cleanup', () => {
        it('should remove from instances on destroy', () => {
            const testElement = document.createElement('div');
            container.appendChild(testElement);

            const testSkeleton = new Skeleton(testElement);

            expect(instances.has(testElement)).toBe(true);

            testSkeleton.destroy();

            expect(instances.has(testElement)).toBe(false);

            container.removeChild(testElement);
        });
    });
});