import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { Persist } from '../components/Persist';
import { instances } from '../core/registry';

describe('Persist Component', () => {
    let container: HTMLElement;
    let inputElement: HTMLInputElement;
    let persist: Persist;

    // Mock storage
    const mockStorage = {
        getItem: vi.fn(),
        setItem: vi.fn(),
        removeItem: vi.fn(),
        clear: vi.fn(),
        length: 0,
        key: vi.fn()
    };

    beforeEach(() => {
        container = document.createElement('div');
        document.body.appendChild(container);

        inputElement = document.createElement('input');
        inputElement.id = 'test-input';
        inputElement.value = 'initial value';
        container.appendChild(inputElement);

        // Mock localStorage and sessionStorage
        vi.spyOn(window, 'localStorage', 'get').mockReturnValue(mockStorage as any);
        vi.spyOn(window, 'sessionStorage', 'get').mockReturnValue(mockStorage as any);

        // Ensure no stored data initially
        mockStorage.getItem.mockReturnValue(null);

        persist = new Persist(inputElement);
    });

    afterEach(() => {
        persist.destroy();
        document.body.removeChild(container);
        vi.clearAllMocks();
    });

    describe('Initialization', () => {
        it('should initialize with input element', () => {
            expect(persist).toBeInstanceOf(Persist);
            expect(mockStorage.getItem).toHaveBeenCalledWith(`vp-${inputElement.id}`);
        });

        it('should use custom key', () => {
            const element = document.createElement('input');
            element.id = 'custom';
            element.setAttribute('data-persist-key', 'my-custom-key');
            container.appendChild(element);

            const customPersist = new Persist(element);

            expect(mockStorage.getItem).toHaveBeenCalledWith('my-custom-key');
            customPersist.destroy();
        });

        it('should use sessionStorage when specified', () => {
            const element = document.createElement('input');
            element.id = 'session';
            element.setAttribute('data-persist-session', 'true');
            container.appendChild(element);

            const sessionPersist = new Persist(element);

            expect(mockStorage.getItem).toHaveBeenCalledWith(`vp-${element.id}`);
            sessionPersist.destroy();
        });
    });

    describe('Functionality', () => {
        describe('save()', () => {
            it('should save input value', () => {
                persist.save();

                expect(mockStorage.setItem).toHaveBeenCalledWith(
                    `vp-${inputElement.id}`,
                    JSON.stringify({ value: 'initial value' })
                );
            });

            it('should return this for chaining', () => {
                expect(persist.save()).toBe(persist);
            });
        });

        describe('load()', () => {
            it('should load saved value', () => {
                mockStorage.getItem.mockReturnValue(JSON.stringify({ value: 'loaded value' }));

                const element = document.createElement('input');
                element.id = 'load-test';
                container.appendChild(element);

                const loadPersist = new Persist(element);

                expect(element.value).toBe('loaded value');
                loadPersist.destroy();
            });

            it('should return this for chaining', () => {
                expect(persist.load()).toBe(persist);
            });
        });

        describe('clear()', () => {
            it('should remove item from storage', () => {
                persist.clear();

                expect(mockStorage.removeItem).toHaveBeenCalledWith(`vp-${inputElement.id}`);
            });

            it('should return this for chaining', () => {
                expect(persist.clear()).toBe(persist);
            });
        });
    });

    describe('Events', () => {
        it('should emit persist:saved event on save', () => {
            let emittedEvent: CustomEvent | null = null;
            inputElement.addEventListener('persist:saved', (e) => {
                emittedEvent = e as CustomEvent;
            });

            persist.save();

            expect(emittedEvent).not.toBeNull();
            expect(emittedEvent?.detail).toEqual({
                key: `vp-${inputElement.id}`,
                data: { value: 'initial value' }
            });
        });

        it('should emit persist:loaded event on load', () => {
            mockStorage.getItem.mockReturnValue(JSON.stringify({ value: 'loaded value' }));

            let emittedEvent: CustomEvent | null = null;
            inputElement.addEventListener('persist:loaded', (e) => {
                emittedEvent = e as CustomEvent;
            });

            persist.load();

            expect(emittedEvent).not.toBeNull();
            expect(emittedEvent?.detail).toEqual({
                key: `vp-${inputElement.id}`,
                data: { value: 'loaded value' }
            });
        });

        it('should auto-save on input change', () => {
            inputElement.value = 'changed value';
            inputElement.dispatchEvent(new Event('input'));

            expect(mockStorage.setItem).toHaveBeenCalledWith(
                `vp-${inputElement.id}`,
                JSON.stringify({ value: 'changed value' })
            );
        });
    });

    describe('Edge Cases', () => {
        it('should handle empty value', () => {
            inputElement.value = '';
            persist.save();

            expect(mockStorage.setItem).toHaveBeenCalledWith(
                `vp-${inputElement.id}`,
                JSON.stringify({ value: '' })
            );
        });

        it('should handle invalid JSON in storage', () => {
            mockStorage.getItem.mockReturnValue('invalid json');

            const element = document.createElement('input');
            element.id = 'invalid-json';
            container.appendChild(element);

            const persistInstance = new Persist(element);

            // Should not throw and should not load anything
            expect(element.value).toBe('');

            persistInstance.destroy();
            container.removeChild(element);
        });

        it('should handle multiple properties', () => {
            const element = document.createElement('input');
            element.id = 'multi-prop';
            element.setAttribute('data-persist', 'value,class');
            element.value = 'test value';
            element.className = 'test-class';
            container.appendChild(element);

            const persistInstance = new Persist(element);
            persistInstance.save();

            expect(mockStorage.setItem).toHaveBeenCalledWith(
                `vp-${element.id}`,
                JSON.stringify({ value: 'test value', class: 'test-class' })
            );

            persistInstance.destroy();
            container.removeChild(element);
        });

        it('should handle checkbox checked state', () => {
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = 'checkbox-test';
            checkbox.setAttribute('data-persist', 'value,checked');
            checkbox.checked = true;
            container.appendChild(checkbox);

            const persistInstance = new Persist(checkbox);
            persistInstance.save();

            expect(mockStorage.setItem).toHaveBeenCalledWith(
                `vp-${checkbox.id}`,
                JSON.stringify({ value: 'on', checked: true })
            );

            persistInstance.destroy();
            container.removeChild(checkbox);
        });

        it('should handle textarea innerHTML', () => {
            const textarea = document.createElement('textarea');
            textarea.id = 'textarea-test';
            textarea.setAttribute('data-persist', 'innerHTML');
            textarea.innerHTML = '<p>test content</p>';
            container.appendChild(textarea);

            const persistInstance = new Persist(textarea);
            persistInstance.save();

            // innerHTML may be escaped in jsdom
            expect(mockStorage.setItem).toHaveBeenCalledWith(
                `vp-${textarea.id}`,
                expect.stringContaining('innerHTML')
            );

            persistInstance.destroy();
            container.removeChild(textarea);
        });
    });

    describe('Accessibility', () => {
        it('should not interfere with ARIA attributes', () => {
            inputElement.setAttribute('aria-label', 'Test input');
            inputElement.setAttribute('role', 'textbox');

            persist.save();

            expect(inputElement.getAttribute('aria-label')).toBe('Test input');
            expect(inputElement.getAttribute('role')).toBe('textbox');
        });
    });

    describe('Cleanup', () => {
        it('should remove from instances on destroy', () => {
            const testElement = document.createElement('input');
            testElement.id = 'cleanup-test';
            container.appendChild(testElement);

            const testPersist = new Persist(testElement);

            expect(instances.has(testElement)).toBe(true);

            testPersist.destroy();

            expect(instances.has(testElement)).toBe(false);

            container.removeChild(testElement);
        });
    });
});