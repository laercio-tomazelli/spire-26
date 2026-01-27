import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { Stepper } from '../components/Stepper';
import { instances } from '../core/registry';

describe('Stepper Component', () => {
    let container: HTMLElement;
    let stepperElement: HTMLElement;
    let stepper: Stepper;

    beforeEach(() => {
        container = document.createElement('div');
        document.body.appendChild(container);

        // Create stepper structure
        stepperElement = document.createElement('div');
        stepperElement.className = 'stepper';
        stepperElement.innerHTML = `
            <div class="stepper-steps">
                <div data-step="1" class="step">
                    <div data-step-indicator></div>
                    <span>Step 1</span>
                </div>
                <div data-step="2" class="step">
                    <div data-step-indicator></div>
                    <span>Step 2</span>
                </div>
                <div data-step="3" class="step">
                    <div data-step-indicator></div>
                    <span>Step 3</span>
                </div>
            </div>
            <div class="stepper-panels">
                <div data-step-panel="1" class="panel">Panel 1</div>
                <div data-step-panel="2" class="panel">Panel 2</div>
                <div data-step-panel="3" class="panel">Panel 3</div>
            </div>
            <div class="stepper-nav">
                <button data-step-prev>Previous</button>
                <button data-step-next>Next</button>
            </div>
        `;
        container.appendChild(stepperElement);

        stepper = new Stepper(stepperElement);
    });

    afterEach(() => {
        stepper.destroy();
        document.body.removeChild(container);
    });

    describe('Initialization', () => {
        it('should initialize with stepper element', () => {
            expect(stepper).toBeInstanceOf(Stepper);
            expect(instances.get(stepperElement)).toBe(stepper);
        });

        it('should find steps and panels', () => {
            expect(stepperElement.querySelectorAll('[data-step]')).toHaveLength(3);
            expect(stepperElement.querySelectorAll('[data-step-panel]')).toHaveLength(3);
        });

        it('should start at step 1 by default', () => {
            expect(stepper.current()).toBe(1);
        });

        it('should respect initial step from dataset', () => {
            const customStepperElement = document.createElement('div');
            customStepperElement.dataset.initialStep = '2';
            customStepperElement.innerHTML = `
                <div data-step="1"></div>
                <div data-step="2"></div>
                <div data-step="3"></div>
                <div data-step-panel="1"></div>
                <div data-step-panel="2"></div>
                <div data-step-panel="3"></div>
            `;
            container.appendChild(customStepperElement);

            const customStepper = new Stepper(customStepperElement);
            expect(customStepper.current()).toBe(2);

            customStepper.destroy();
            container.removeChild(customStepperElement);
        });

        it('should be linear by default', () => {
            expect(stepperElement.dataset.linear).toBeUndefined();
            // Test that linear behavior works
            expect(stepper.canGoTo(3)).toBe(false); // Cannot jump to step 3 initially
        });

        it('should support non-linear mode', () => {
            const nonLinearElement = document.createElement('div');
            nonLinearElement.dataset.linear = 'false';
            nonLinearElement.innerHTML = `
                <div data-step="1"></div>
                <div data-step="2"></div>
                <div data-step="3"></div>
                <div data-step-panel="1"></div>
                <div data-step-panel="2"></div>
                <div data-step-panel="3"></div>
            `;
            container.appendChild(nonLinearElement);

            const nonLinearStepper = new Stepper(nonLinearElement);
            expect(nonLinearStepper.canGoTo(3)).toBe(true); // Can jump to any step

            nonLinearStepper.destroy();
            container.removeChild(nonLinearElement);
        });
    });

    describe('Functionality', () => {
        it('goto() should navigate to specific step', () => {
            // Complete step 1 first to allow going to step 2 in linear mode
            stepper.complete(1);
            stepper.goto(2);
            expect(stepper.current()).toBe(2);
        });

        it('goto() should not navigate to invalid steps', () => {
            stepper.goto(0);
            expect(stepper.current()).toBe(1);

            stepper.goto(10);
            expect(stepper.current()).toBe(1);
        });

        it('goto() should respect linear mode restrictions', () => {
            stepper.goto(3);
            expect(stepper.current()).toBe(1); // Should not move
        });

        it('next() should move to next step', () => {
            stepper.next();
            expect(stepper.current()).toBe(2);
        });

        it('next() should complete current step', () => {
            const mockEmit = vi.fn();
            stepperElement.addEventListener('stepper:completed', mockEmit);

            stepper.next();

            expect(mockEmit).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({ step: 1 })
                })
            );
        });

        it('prev() should move to previous step', () => {
            stepper.complete(1).goto(2).complete(2).goto(3).prev();
            expect(stepper.current()).toBe(2);
        });

        it('complete() should mark step as completed', () => {
            stepper.complete(1);
            expect(stepper.canGoTo(2)).toBe(true); // Can now go to step 2
        });

        it('complete() should work with current step by default', () => {
            stepper.complete();
            expect(stepper.canGoTo(2)).toBe(true);
        });

        it('reset() should clear completed steps and go to first step', () => {
            stepper.complete(1).goto(2).complete(2).reset();
            expect(stepper.current()).toBe(1);
            expect(stepper.canGoTo(2)).toBe(false); // Completed steps cleared
        });

        it('canNext() should return true when not at last step', () => {
            expect(stepper.canNext()).toBe(true);
            stepper.complete(1).goto(2).complete(2).goto(3);
            expect(stepper.canNext()).toBe(false);
        });

        it('canPrev() should return true when not at first step', () => {
            expect(stepper.canPrev()).toBe(false);
            stepper.complete(1).goto(2);
            expect(stepper.canPrev()).toBe(true);
        });

        it('should return this for method chaining', () => {
            expect(stepper.goto(2)).toBe(stepper);
            expect(stepper.next()).toBe(stepper);
            expect(stepper.prev()).toBe(stepper);
            expect(stepper.complete()).toBe(stepper);
            expect(stepper.reset()).toBe(stepper);
        });
    });

    describe('Events', () => {
        it('should emit stepper:change on goto', () => {
            const mockEmit = vi.fn();
            stepperElement.addEventListener('stepper:change', mockEmit);

            stepper.complete(1).goto(2);

            expect(mockEmit).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        from: 1,
                        to: 2,
                        completed: [1]
                    })
                })
            );
        });

        it('should emit stepper:completed on complete', () => {
            const mockEmit = vi.fn();
            stepperElement.addEventListener('stepper:completed', mockEmit);

            stepper.complete(1);

            expect(mockEmit).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        step: 1,
                        allCompleted: false
                    })
                })
            );
        });

        it('should emit stepper:completed with allCompleted true when all steps done', () => {
            const mockEmit = vi.fn();
            stepperElement.addEventListener('stepper:completed', mockEmit);

            stepper.complete(1).complete(2).complete(3);

            expect(mockEmit).toHaveBeenLastCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        step: 3,
                        allCompleted: true
                    })
                })
            );
        });

        it('should emit stepper:reset on reset', () => {
            const mockEmit = vi.fn();
            stepperElement.addEventListener('stepper:reset', mockEmit);

            stepper.reset();

            expect(mockEmit).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({})
                })
            );
        });
    });

    describe('Accessibility', () => {
        it('should set up ARIA attributes on initialization', () => {
            expect(stepperElement.getAttribute('role')).toBe('navigation');
            expect(stepperElement.getAttribute('aria-label')).toBe('Progresso');

            const steps = stepperElement.querySelectorAll('[data-step]');
            expect(steps[0].getAttribute('role')).toBe('tab');
            expect(steps[0].getAttribute('aria-selected')).toBe('true');
            expect(steps[1].getAttribute('aria-selected')).toBe('false');

            const panels = stepperElement.querySelectorAll('[data-step-panel]');
            panels.forEach(panel => {
                expect(panel.getAttribute('role')).toBe('tabpanel');
            });
        });

        it('should update ARIA attributes on navigation', () => {
            stepper.complete(1).goto(2);

            const steps = stepperElement.querySelectorAll('[data-step]');
            expect(steps[0].getAttribute('aria-selected')).toBe('false');
            expect(steps[0].getAttribute('aria-current')).toBe('false');
            expect(steps[1].getAttribute('aria-selected')).toBe('true');
            expect(steps[1].getAttribute('aria-current')).toBe('step');

            const panels = stepperElement.querySelectorAll('[data-step-panel]');
            expect(panels[0].getAttribute('aria-hidden')).toBe('true');
            expect(panels[1].getAttribute('aria-hidden')).toBe('false');
        });
    });

    describe('UI Updates', () => {
        it('should update step classes on navigation', () => {
            const steps = stepperElement.querySelectorAll('[data-step]');

            // Initial state
            expect(steps[0].classList.contains('active')).toBe(true);
            expect(steps[0].classList.contains('completed')).toBe(false);

            // Go to step 2
            stepper.complete(1).goto(2);
            expect(steps[0].classList.contains('active')).toBe(false);
            expect(steps[0].classList.contains('completed')).toBe(true);
            expect(steps[1].classList.contains('active')).toBe(true);

            // Complete step 1 and go to step 2
            stepper.goto(1).complete(1).goto(2);
            expect(steps[0].classList.contains('completed')).toBe(true);
        });

        it('should update step indicators', () => {
            const indicators = stepperElement.querySelectorAll('[data-step-indicator]');

            // Initially show numbers
            expect(indicators[0].textContent).toBe('1');

            // After completion, show checkmark
            stepper.complete(1);
            expect(indicators[0].innerHTML).toContain('svg');
        });

        it('should show/hide panels correctly', () => {
            const panels = stepperElement.querySelectorAll('[data-step-panel]');

            expect(panels[0].classList.contains('hidden')).toBe(false);
            expect(panels[1].classList.contains('hidden')).toBe(true);

            stepper.complete(1).goto(2);
            expect(panels[0].classList.contains('hidden')).toBe(true);
            expect(panels[1].classList.contains('hidden')).toBe(false);
        });

        it('should enable/disable navigation buttons', () => {
            const prevBtn = stepperElement.querySelector('[data-step-prev]') as HTMLButtonElement;
            const nextBtn = stepperElement.querySelector('[data-step-next]') as HTMLButtonElement;

            expect(prevBtn.disabled).toBe(true);
            expect(nextBtn.disabled).toBe(false);

            stepper.complete(1).goto(2).complete(2).goto(3);
            expect(prevBtn.disabled).toBe(false);
            expect(nextBtn.disabled).toBe(true);
        });
    });

    describe('Click Interactions', () => {
        it('should navigate on step click', () => {
            const steps = stepperElement.querySelectorAll('[data-step]');
            stepper.complete(1);
            (steps[1] as HTMLElement).click();

            expect(stepper.current()).toBe(2);
        });

        it('should respect linear mode on step click', () => {
            const steps = stepperElement.querySelectorAll('[data-step]');
            (steps[2] as HTMLElement).click();

            expect(stepper.current()).toBe(1); // Should not move
        });

        it('should allow jumping in non-linear mode', () => {
            const nonLinearElement = document.createElement('div');
            nonLinearElement.dataset.linear = 'false';
            nonLinearElement.innerHTML = `
                <div data-step="1"></div>
                <div data-step="2"></div>
                <div data-step="3"></div>
                <div data-step-panel="1"></div>
                <div data-step-panel="2"></div>
                <div data-step-panel="3"></div>
            `;
            container.appendChild(nonLinearElement);

            const nonLinearStepper = new Stepper(nonLinearElement);
            const steps = nonLinearElement.querySelectorAll('[data-step]');
            (steps[2] as HTMLElement).click();

            expect(nonLinearStepper.current()).toBe(3);

            nonLinearStepper.destroy();
            container.removeChild(nonLinearElement);
        });

        it('should handle next button click', () => {
            const nextBtn = stepperElement.querySelector('[data-step-next]');
            (nextBtn as HTMLElement).click();

            expect(stepper.current()).toBe(2);
        });

        it('should handle prev button click', () => {
            stepper.goto(2);
            const prevBtn = stepperElement.querySelector('[data-step-prev]');
            (prevBtn as HTMLElement).click();

            expect(stepper.current()).toBe(1);
        });
    });

    describe('Edge Cases', () => {
        it('should handle single step stepper', () => {
            const singleStepElement = document.createElement('div');
            singleStepElement.innerHTML = `
                <div data-step="1"></div>
                <div data-step-panel="1"></div>
            `;
            container.appendChild(singleStepElement);

            const singleStepper = new Stepper(singleStepElement);
            expect(singleStepper.current()).toBe(1);
            expect(singleStepper.canNext()).toBe(false);
            expect(singleStepper.canPrev()).toBe(false);

            singleStepper.destroy();
            container.removeChild(singleStepElement);
        });

        it('should handle empty stepper gracefully', () => {
            const emptyElement = document.createElement('div');
            container.appendChild(emptyElement);

            const emptyStepper = new Stepper(emptyElement);
            expect(emptyStepper.current()).toBe(1);
            expect(emptyStepper.canNext()).toBe(false);
            expect(emptyStepper.canPrev()).toBe(false);

            emptyStepper.destroy();
            container.removeChild(emptyElement);
        });

        it('should handle invalid initial step', () => {
            const invalidStepElement = document.createElement('div');
            invalidStepElement.dataset.initialStep = '10';
            invalidStepElement.innerHTML = `
                <div data-step="1"></div>
                <div data-step="2"></div>
                <div data-step-panel="1"></div>
                <div data-step-panel="2"></div>
            `;
            container.appendChild(invalidStepElement);

            const invalidStepper = new Stepper(invalidStepElement);
            expect(invalidStepper.current()).toBe(1); // Should default to 1

            invalidStepper.destroy();
            container.removeChild(invalidStepElement);
        });
    });

    describe('Cleanup', () => {
        it('should remove from instances on destroy', () => {
            expect(instances.get(stepperElement)).toBe(stepper);
            stepper.destroy();
            expect(instances.get(stepperElement)).toBeUndefined();
        });
    });
});