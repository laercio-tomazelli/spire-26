import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { MultiSelect } from '../components/MultiSelect';

describe('MultiSelect Component', () => {
    let container: HTMLElement;
    let multiSelectEl: HTMLElement;
    let multiSelect: MultiSelect;

    beforeEach(() => {
        container = document.createElement('div');
        document.body.appendChild(container);

        multiSelectEl = document.createElement('div');
        multiSelectEl.id = 'test-multiselect';
        multiSelectEl.innerHTML = `
            <div data-multiselect-trigger class="border rounded-lg p-3 cursor-pointer">
                <div data-multiselect-tags></div>
            </div>
            <div data-multiselect-dropdown class="hidden absolute mt-1 w-full bg-white border rounded-lg shadow-lg z-10">
                <input data-multiselect-search type="text" class="w-full p-2 border-b" placeholder="Buscar...">
                <div data-multiselect-options>
                    <div data-option="option1">Option 1</div>
                    <div data-option="option2">Option 2</div>
                    <div data-option="option3">Option 3</div>
                </div>
                <div class="p-2 border-t flex gap-2">
                    <button data-select-all class="text-blue-600 text-sm">Selecionar tudo</button>
                    <button data-clear-all class="text-gray-600 text-sm">Limpar</button>
                </div>
            </div>
            <input type="hidden" name="test">
        `;
        container.appendChild(multiSelectEl);

        multiSelect = new MultiSelect(multiSelectEl);
    });

    afterEach(() => {
        multiSelect.destroy();
        document.body.removeChild(container);
        vi.clearAllMocks();
    });

    describe('Initialization', () => {
        it('should initialize with multiselect element', () => {
            expect(multiSelectEl.querySelector('[data-multiselect-trigger]')).toBeTruthy();
            expect(multiSelectEl.querySelector('[data-multiselect-dropdown]')).toBeTruthy();
            expect(multiSelectEl.querySelector('[data-multiselect-options]')).toBeTruthy();
        });

        it('should parse options correctly', () => {
            const options = multiSelectEl.querySelectorAll('[data-option]');
            expect(options).toHaveLength(3);
        });

        it('should handle custom placeholder', () => {
            const customEl = document.createElement('div');
            customEl.dataset.placeholder = 'Custom placeholder';
            customEl.innerHTML = `
                <div data-multiselect-trigger>
                    <div data-multiselect-tags></div>
                </div>
                <div data-multiselect-dropdown class="hidden">
                    <div data-multiselect-options></div>
                </div>
            `;
            container.appendChild(customEl);

            const customSelect = new MultiSelect(customEl);
            expect(customEl.querySelector('[data-multiselect-tags]')?.textContent).toContain('Custom placeholder');

            customSelect.destroy();
            container.removeChild(customEl);
        });

        it('should handle max items', () => {
            const customEl = document.createElement('div');
            customEl.dataset.maxItems = '2';
            customEl.innerHTML = `
                <div data-multiselect-trigger>
                    <div data-multiselect-tags></div>
                </div>
                <div data-multiselect-dropdown class="hidden">
                    <div data-multiselect-options>
                        <div data-option="a">A</div>
                        <div data-option="b">B</div>
                        <div data-option="c">C</div>
                    </div>
                </div>
            `;
            container.appendChild(customEl);

            const customSelect = new MultiSelect(customEl);
            expect(customSelect).toBeDefined();

            customSelect.destroy();
            container.removeChild(customEl);
        });

        it('should handle searchable option', () => {
            const customEl = document.createElement('div');
            customEl.dataset.searchable = 'false';
            customEl.innerHTML = `
                <div data-multiselect-trigger>
                    <div data-multiselect-tags></div>
                </div>
                <div data-multiselect-dropdown class="hidden">
                    <div data-multiselect-options></div>
                </div>
            `;
            container.appendChild(customEl);

            const customSelect = new MultiSelect(customEl);
            expect(customSelect).toBeDefined();

            customSelect.destroy();
            container.removeChild(customEl);
        });
    });

    describe('Selection Functionality', () => {
        it('should add option', () => {
            multiSelect.add('option1');

            expect(multiSelect.value()).toContain('option1');
            expect(multiSelect.value()).toHaveLength(1);
        });

        it('should remove option', () => {
            multiSelect.add('option1');
            expect(multiSelect.value()).toHaveLength(1);

            multiSelect.remove('option1');

            expect(multiSelect.value()).toHaveLength(0);
            expect(multiSelect.value()).not.toContain('option1');
        });

        it('should toggle option', () => {
            multiSelect.toggle('option1');
            expect(multiSelect.value()).toContain('option1');

            multiSelect.toggle('option1');
            expect(multiSelect.value()).not.toContain('option1');
        });

        it('should not add duplicate options', () => {
            multiSelect.add('option1');
            multiSelect.add('option1');

            expect(multiSelect.value()).toHaveLength(1);
        });

        it('should respect max items limit', () => {
            const customEl = document.createElement('div');
            customEl.dataset.maxItems = '2';
            customEl.innerHTML = `
                <div data-multiselect-trigger>
                    <div data-multiselect-tags></div>
                </div>
                <div data-multiselect-dropdown class="hidden">
                    <div data-multiselect-options>
                        <div data-option="a">A</div>
                        <div data-option="b">B</div>
                        <div data-option="c">C</div>
                    </div>
                </div>
            `;
            container.appendChild(customEl);

            const customSelect = new MultiSelect(customEl);

            customSelect.add('a');
            customSelect.add('b');
            customSelect.add('c'); // Should not be added

            expect(customSelect.value()).toHaveLength(2);
            expect(customSelect.value()).not.toContain('c');

            customSelect.destroy();
            container.removeChild(customEl);
        });
    });

    describe('Display Updates', () => {
        it('should update hidden input', () => {
            const hiddenInput = multiSelectEl.querySelector('input[type="hidden"]') as HTMLInputElement;

            multiSelect.add('option1');
            multiSelect.add('option2');

            expect(JSON.parse(hiddenInput.value)).toEqual(['option1', 'option2']);
        });

        it('should update tags display', () => {
            const tagsContainer = multiSelectEl.querySelector('[data-multiselect-tags]');

            multiSelect.add('option1');

            expect(tagsContainer?.innerHTML).toContain('Option 1');
            expect(tagsContainer?.querySelector('[data-remove-tag]')).toBeTruthy();
        });

        it('should show placeholder when no selection', () => {
            const tagsContainer = multiSelectEl.querySelector('[data-multiselect-tags]');

            expect(tagsContainer?.textContent).toContain('Selecione...');
        });

        it('should update option selected state', () => {
            const option1 = multiSelectEl.querySelector('[data-option="option1"]');

            multiSelect.add('option1');

            expect(option1?.classList.contains('selected')).toBe(true);
            expect(option1?.getAttribute('aria-selected')).toBe('true');
        });
    });

    describe('Dropdown Functionality', () => {
        it('should open dropdown', () => {
            const dropdown = multiSelectEl.querySelector('[data-multiselect-dropdown]');
            const trigger = multiSelectEl.querySelector('[data-multiselect-trigger]');

            multiSelect.open();

            expect(dropdown?.classList.contains('hidden')).toBe(false);
            expect(trigger?.getAttribute('aria-expanded')).toBe('true');
        });

        it('should close dropdown', () => {
            multiSelect.open();
            multiSelect.close();

            const dropdown = multiSelectEl.querySelector('[data-multiselect-dropdown]');
            const trigger = multiSelectEl.querySelector('[data-multiselect-trigger]');

            expect(dropdown?.classList.contains('hidden')).toBe(true);
            expect(trigger?.getAttribute('aria-expanded')).toBe('false');
        });

        it('should toggle dropdown on trigger click', () => {
            const trigger = multiSelectEl.querySelector('[data-multiselect-trigger]') as HTMLElement;

            trigger.click();
            expect(multiSelectEl.querySelector('[data-multiselect-dropdown]')?.classList.contains('hidden')).toBe(false);

            trigger.click();
            expect(multiSelectEl.querySelector('[data-multiselect-dropdown]')?.classList.contains('hidden')).toBe(true);
        });

        it('should close on outside click', () => {
            multiSelect.open();

            document.body.click();

            expect(multiSelectEl.querySelector('[data-multiselect-dropdown]')?.classList.contains('hidden')).toBe(true);
        });

        it('should close on escape key', () => {
            multiSelect.open();

            const event = new KeyboardEvent('keydown', { key: 'Escape' });
            document.dispatchEvent(event);

            expect(multiSelectEl.querySelector('[data-multiselect-dropdown]')?.classList.contains('hidden')).toBe(true);
        });
    });

    describe('Search Functionality', () => {
        it('should filter options on search', () => {
            const searchInput = multiSelectEl.querySelector('[data-multiselect-search]') as HTMLInputElement;

            searchInput.value = 'Option 1';
            searchInput.dispatchEvent(new Event('input'));

            const option1 = multiSelectEl.querySelector('[data-option="option1"]') as HTMLElement;
            const option2 = multiSelectEl.querySelector('[data-option="option2"]') as HTMLElement;

            expect(option1.style.display).toBe('');
            expect(option2.style.display).toBe('none');
        });

        it('should show all options when search is empty', () => {
            const searchInput = multiSelectEl.querySelector('[data-multiselect-search]') as HTMLInputElement;

            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));

            const options = multiSelectEl.querySelectorAll('[data-option]');
            options.forEach(option => {
                expect((option as HTMLElement).style.display).toBe('');
            });
        });
    });

    describe('Bulk Operations', () => {
        it('should select all options', () => {
            multiSelect.selectAll();

            expect(multiSelect.value()).toHaveLength(3);
            expect(multiSelect.value()).toEqual(['option1', 'option2', 'option3']);
        });

        it('should clear all selections', () => {
            multiSelect.add('option1');
            multiSelect.add('option2');
            expect(multiSelect.value()).toHaveLength(2);

            multiSelect.clear();

            expect(multiSelect.value()).toHaveLength(0);
        });

        it('should respect max items in select all', () => {
            const customEl = document.createElement('div');
            customEl.dataset.maxItems = '2';
            customEl.innerHTML = `
                <div data-multiselect-trigger>
                    <div data-multiselect-tags></div>
                </div>
                <div data-multiselect-dropdown class="hidden">
                    <div data-multiselect-options>
                        <div data-option="a">A</div>
                        <div data-option="b">B</div>
                        <div data-option="c">C</div>
                    </div>
                </div>
            `;
            container.appendChild(customEl);

            const customSelect = new MultiSelect(customEl);
            customSelect.selectAll();

            expect(customSelect.value()).toHaveLength(2);

            customSelect.destroy();
            container.removeChild(customEl);
        });
    });

    describe('Events', () => {
        it('should emit change event', () => {
            const eventSpy = vi.fn();
            multiSelectEl.addEventListener('multiselect:change', eventSpy);

            multiSelect.setValue(['option1']);

            expect(eventSpy).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        values: ['option1']
                    })
                })
            );
        });

        it('should emit added event', () => {
            const eventSpy = vi.fn();
            multiSelectEl.addEventListener('multiselect:added', eventSpy);

            multiSelect.add('option1');

            expect(eventSpy).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        value: 'option1',
                        values: ['option1']
                    })
                })
            );
        });

        it('should emit removed event', () => {
            multiSelect.add('option1');

            const eventSpy = vi.fn();
            multiSelectEl.addEventListener('multiselect:removed', eventSpy);

            multiSelect.remove('option1');

            expect(eventSpy).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        value: 'option1',
                        values: []
                    })
                })
            );
        });

        it('should emit max-reached event', () => {
            const customEl = document.createElement('div');
            customEl.dataset.maxItems = '1';
            customEl.innerHTML = `
                <div data-multiselect-trigger>
                    <div data-multiselect-tags></div>
                </div>
                <div data-multiselect-dropdown class="hidden">
                    <div data-multiselect-options>
                        <div data-option="a">A</div>
                        <div data-option="b">B</div>
                    </div>
                </div>
            `;
            container.appendChild(customEl);

            const customSelect = new MultiSelect(customEl);
            customSelect.add('a');

            const eventSpy = vi.fn();
            customEl.addEventListener('multiselect:max-reached', eventSpy);

            customSelect.add('b'); // Should trigger max-reached

            expect(eventSpy).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        max: 1
                    })
                })
            );

            customSelect.destroy();
            container.removeChild(customEl);
        });
    });

    describe('Accessibility', () => {
        it('should set correct ARIA attributes', () => {
            const trigger = multiSelectEl.querySelector('[data-multiselect-trigger]');
            const optionsList = multiSelectEl.querySelector('[data-multiselect-options]');

            expect(trigger?.getAttribute('role')).toBe('combobox');
            expect(trigger?.getAttribute('aria-haspopup')).toBe('listbox');
            expect(trigger?.getAttribute('aria-multiselectable')).toBe('true');
            expect(optionsList?.getAttribute('role')).toBe('listbox');
        });

        it('should update aria-expanded on open/close', () => {
            const trigger = multiSelectEl.querySelector('[data-multiselect-trigger]');

            multiSelect.open();
            expect(trigger?.getAttribute('aria-expanded')).toBe('true');

            multiSelect.close();
            expect(trigger?.getAttribute('aria-expanded')).toBe('false');
        });
    });

    describe('Tag Removal', () => {
        it('should remove tag on remove button click', () => {
            multiSelect.add('option1');

            const removeBtn = multiSelectEl.querySelector('[data-remove-tag]') as HTMLElement;
            removeBtn.click();

            expect(multiSelect.value()).toHaveLength(0);
        });
    });

    describe('Value Management', () => {
        it('should set value directly', () => {
            multiSelect.setValue(['option1', 'option3']);

            expect(multiSelect.value()).toEqual(['option1', 'option3']);
        });

        it('should return current value', () => {
            multiSelect.add('option1');
            multiSelect.add('option2');

            expect(multiSelect.value()).toEqual(['option1', 'option2']);
        });
    });

    describe('Options Management', () => {
        it('should set options programmatically', () => {
            const newOptions = [
                { value: 'new1', label: 'New Option 1' },
                { value: 'new2', label: 'New Option 2' }
            ];

            multiSelect.options(newOptions);

            const options = multiSelectEl.querySelectorAll('[data-option]');
            expect(options).toHaveLength(2);
            expect(options[0].textContent).toContain('New Option 1');
        });
    });

    describe('Cleanup', () => {
        it('should destroy without errors', () => {
            expect(() => multiSelect.destroy()).not.toThrow();
        });
    });

    describe('Performance', () => {
        it('should handle multiple selections efficiently', () => {
            const startTime = Date.now();

            for (let i = 0; i < 10; i++) {
                multiSelect.add(`option${(i % 3) + 1}`);
                multiSelect.remove(`option${(i % 3) + 1}`);
            }

            const endTime = Date.now();
            const duration = endTime - startTime;

            expect(duration).toBeLessThan(200); // Should complete reasonably quickly
        });

        it('should handle rapid open/close operations', () => {
            // Rapid open/close operations
            for (let i = 0; i < 5; i++) {
                multiSelect.open();
                multiSelect.close();
            }

            expect(multiSelectEl.querySelector('[data-multiselect-dropdown]')?.classList.contains('hidden')).toBe(true);
        });
    });
});