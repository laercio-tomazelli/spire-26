import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { Clipboard, setToast } from '../components/Clipboard';

// Mock navigator.clipboard
Object.defineProperty(navigator, 'clipboard', {
    value: {
        writeText: vi.fn(),
    },
    writable: true,
});

describe('Clipboard Component', () => {
    let buttonEl: HTMLElement;
    let inputEl: HTMLInputElement;
    let textareaEl: HTMLTextAreaElement;
    let clipboard: Clipboard;
    let mockToast: { error: vi.MockedFunction<any> };

    beforeEach(() => {
        vi.useFakeTimers();
        // Setup mock toast
        mockToast = { error: vi.fn() };
        setToast(mockToast);

        // Setup elements
        buttonEl = document.createElement('button');
        buttonEl.textContent = 'Copy';
        document.body.appendChild(buttonEl);

        inputEl = document.createElement('input');
        inputEl.type = 'text';
        inputEl.value = 'Text to copy';
        document.body.appendChild(inputEl);

        textareaEl = document.createElement('textarea');
        textareaEl.value = 'Textarea content';
        document.body.appendChild(textareaEl);

        // Reset clipboard mock
        (navigator.clipboard.writeText as vi.MockedFunction<any>).mockClear();
        (navigator.clipboard.writeText as vi.MockedFunction<any>).mockResolvedValue(undefined);
    });

    afterEach(() => {
        vi.restoreAllMocks();
        document.body.removeChild(buttonEl);
        document.body.removeChild(inputEl);
        document.body.removeChild(textareaEl);
    });

    describe('Initialization', () => {
        it('should initialize with text content', () => {
            const textEl = document.createElement('button');
            textEl.textContent = 'Copy this text';
            document.body.appendChild(textEl);
            const textClipboard = new Clipboard(textEl);

            expect(textClipboard).toBeDefined();
            document.body.removeChild(textEl);
        });

        it('should initialize with data-clipboard-text attribute', () => {
            const dataEl = document.createElement('button');
            dataEl.setAttribute('data-clipboard-text', 'Custom text');
            document.body.appendChild(dataEl);
            const dataClipboard = new Clipboard(dataEl);

            expect(dataClipboard).toBeDefined();
            document.body.removeChild(dataEl);
        });

        it('should initialize with data-clipboard-target attribute', () => {
            const targetEl = document.createElement('button');
            targetEl.setAttribute('data-clipboard-target', '#test-input');
            document.body.appendChild(targetEl);
            const targetClipboard = new Clipboard(targetEl);

            expect(targetClipboard).toBeDefined();
            document.body.removeChild(targetEl);
        });

        it('should use custom success message', () => {
            const customEl = document.createElement('button');
            customEl.setAttribute('data-success-message', 'Copied successfully!');
            customEl.textContent = 'Copy';
            document.body.appendChild(customEl);
            const customClipboard = new Clipboard(customEl);

            expect(customClipboard).toBeDefined();
            document.body.removeChild(customEl);
        });

        it('should use custom error message', () => {
            const customEl = document.createElement('button');
            customEl.setAttribute('data-error-message', 'Copy failed!');
            customEl.textContent = 'Copy';
            document.body.appendChild(customEl);
            const customClipboard = new Clipboard(customEl);

            expect(customClipboard).toBeDefined();
            document.body.removeChild(customEl);
        });
    });

    describe('Copy Methods', () => {
        it('should copy text from target input element', async () => {
            buttonEl.setAttribute('data-clipboard-target', '#test-input');
            inputEl.id = 'test-input';
            const targetClipboard = new Clipboard(buttonEl);

            const result = await targetClipboard.copy();
            expect(result).toBe(true);
            expect(navigator.clipboard.writeText).toHaveBeenCalledWith('Text to copy');
        });

        it('should copy text from target textarea element', async () => {
            buttonEl.setAttribute('data-clipboard-target', '#test-textarea');
            textareaEl.id = 'test-textarea';
            const targetClipboard = new Clipboard(buttonEl);

            const result = await targetClipboard.copy();
            expect(result).toBe(true);
            expect(navigator.clipboard.writeText).toHaveBeenCalledWith('Textarea content');
        });

        it('should copy text from data-clipboard-text attribute', async () => {
            buttonEl.setAttribute('data-clipboard-text', 'Attribute text');
            const attrClipboard = new Clipboard(buttonEl);

            const result = await attrClipboard.copy();
            expect(result).toBe(true);
            expect(navigator.clipboard.writeText).toHaveBeenCalledWith('Attribute text');
        });

        it('should copy text from element content', async () => {
            const contentClipboard = new Clipboard(buttonEl);

            const result = await contentClipboard.copy();
            expect(result).toBe(true);
            expect(navigator.clipboard.writeText).toHaveBeenCalledWith('Copy');
        });

        it('should copy custom text using copyText method', async () => {
            const customClipboard = new Clipboard(buttonEl);

            const result = await customClipboard.copyText('Custom text');
            expect(result).toBe(true);
            expect(navigator.clipboard.writeText).toHaveBeenCalledWith('Custom text');
        });

        it('should handle empty text gracefully', async () => {
            const emptyEl = document.createElement('button');
            document.body.appendChild(emptyEl);
            const emptyClipboard = new Clipboard(emptyEl);

            const result = await emptyClipboard.copy();
            expect(result).toBe(true);
            expect(navigator.clipboard.writeText).toHaveBeenCalledWith('');

            document.body.removeChild(emptyEl);
        });
    });

    describe('Visual Feedback', () => {
        it('should show success feedback on copy', async () => {
            const feedbackClipboard = new Clipboard(buttonEl);

            const result = await feedbackClipboard.copy();
            expect(result).toBe(true);

            // Check if success icon and message are shown
            expect(buttonEl.innerHTML).toContain('M5 13l4 4L19 7'); // Check mark SVG path
            expect(buttonEl.innerHTML).toContain('Copiado!');
            expect(buttonEl.classList.contains('text-green-600')).toBe(true);
        });

        it('should restore original content after timeout', async () => {
            const originalHTML = buttonEl.innerHTML;
            const feedbackClipboard = new Clipboard(buttonEl);

            await feedbackClipboard.copy();

            // Fast-forward timers
            vi.advanceTimersByTime(2000);

            expect(buttonEl.innerHTML).toBe(originalHTML);
            expect(buttonEl.classList.contains('text-green-600')).toBe(false);
        });

        it('should use custom success message', async () => {
            buttonEl.setAttribute('data-success-message', 'Copied successfully!');
            const customClipboard = new Clipboard(buttonEl);

            await customClipboard.copy();

            expect(buttonEl.innerHTML).toContain('Copied successfully!');
        });
    });

    describe('Events', () => {
        it('should emit clipboard:copied event on success', async () => {
            const eventClipboard = new Clipboard(buttonEl);
            const mockCallback = vi.fn();
            buttonEl.addEventListener('clipboard:copied', mockCallback);

            await eventClipboard.copy();

            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: { text: 'Copy' }
                })
            );
        });

        it('should emit clipboard:error event on failure', async () => {
            (navigator.clipboard.writeText as vi.MockedFunction<any>).mockRejectedValue(new Error('Clipboard error'));
            const eventClipboard = new Clipboard(buttonEl);
            const mockCallback = vi.fn();
            buttonEl.addEventListener('clipboard:error', mockCallback);

            const result = await eventClipboard.copy();
            expect(result).toBe(false);

            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: { text: 'Copy' }
                })
            );
        });

        it('should trigger copy on click', async () => {
            const clickClipboard = new Clipboard(buttonEl);

            buttonEl.click();

            expect(navigator.clipboard.writeText).toHaveBeenCalledWith('Copy');
        });
    });

    describe('Error Handling', () => {
        it('should handle clipboard API errors', async () => {
            (navigator.clipboard.writeText as vi.MockedFunction<any>).mockRejectedValue(new Error('Clipboard not supported'));
            const errorClipboard = new Clipboard(buttonEl);

            const result = await errorClipboard.copy();
            expect(result).toBe(false);
            expect(mockToast.error).toHaveBeenCalledWith('Erro ao copiar');
        });

        it('should use custom error message', async () => {
            (navigator.clipboard.writeText as vi.MockedFunction<any>).mockRejectedValue(new Error('Error'));
            buttonEl.setAttribute('data-error-message', 'Custom error message');
            const customErrorClipboard = new Clipboard(buttonEl);

            await customErrorClipboard.copy();

            expect(mockToast.error).toHaveBeenCalledWith('Custom error message');
        });

        it('should not show visual feedback on error', async () => {
            (navigator.clipboard.writeText as vi.MockedFunction<any>).mockRejectedValue(new Error('Error'));
            const originalHTML = buttonEl.innerHTML;
            const errorClipboard = new Clipboard(buttonEl);

            await errorClipboard.copy();

            expect(buttonEl.innerHTML).toBe(originalHTML);
            expect(buttonEl.classList.contains('text-green-600')).toBe(false);
        });
    });

    describe('Edge Cases', () => {
        it('should handle non-existent target gracefully', async () => {
            buttonEl.setAttribute('data-clipboard-target', '#non-existent');
            const missingTargetClipboard = new Clipboard(buttonEl);

            const result = await missingTargetClipboard.copy();
            expect(result).toBe(true);
            expect(navigator.clipboard.writeText).toHaveBeenCalledWith('Copy');
        });

        it('should handle invalid target selector gracefully', async () => {
            buttonEl.setAttribute('data-clipboard-target', 'invalid-selector');
            const invalidTargetClipboard = new Clipboard(buttonEl);

            const result = await invalidTargetClipboard.copy();
            expect(result).toBe(true);
            expect(navigator.clipboard.writeText).toHaveBeenCalledWith('Copy');
        });

        it('should handle target without value property', async () => {
            const divEl = document.createElement('div');
            divEl.id = 'div-target';
            divEl.textContent = 'Div content';
            document.body.appendChild(divEl);

            buttonEl.setAttribute('data-clipboard-target', '#div-target');
            const divTargetClipboard = new Clipboard(buttonEl);

            const result = await divTargetClipboard.copy();
            expect(result).toBe(true);
            expect(navigator.clipboard.writeText).toHaveBeenCalledWith('Copy');

            document.body.removeChild(divEl);
        });
    });

    describe('Cleanup', () => {
        it('should destroy without errors', () => {
            const destroyClipboard = new Clipboard(buttonEl);
            expect(() => destroyClipboard.destroy()).not.toThrow();
        });
    });

    describe('Performance', () => {
        it('should handle multiple rapid clicks efficiently', async () => {
            const perfClipboard = new Clipboard(buttonEl);

            // Simulate multiple rapid clicks
            const promises = [];
            for (let i = 0; i < 10; i++) {
                promises.push(perfClipboard.copy());
            }

            const results = await Promise.all(promises);
            results.forEach(result => expect(result).toBe(true));
            expect(navigator.clipboard.writeText).toHaveBeenCalledTimes(10);
        });
    });
});