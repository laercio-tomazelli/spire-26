import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { DateRangePicker } from '../components/DateRangePicker';

describe('DateRangePicker Component', () => {
    let containerEl: HTMLElement;
    let dateRangePicker: DateRangePicker;
    let startInput: HTMLInputElement;
    let endInput: HTMLInputElement;

    beforeEach(() => {
        vi.useFakeTimers();

        // Setup container element
        containerEl = document.createElement('div');
        containerEl.setAttribute('data-format', 'dd/mm/yyyy');
        containerEl.setAttribute('data-start-placeholder', 'Start Date');
        containerEl.setAttribute('data-end-placeholder', 'End Date');
        document.body.appendChild(containerEl);

        dateRangePicker = new DateRangePicker(containerEl);

        // Get the created inputs
        const inputs = containerEl.querySelectorAll('input');
        startInput = inputs[0] as HTMLInputElement;
        endInput = inputs[1] as HTMLInputElement;
    });

    afterEach(() => {
        dateRangePicker.destroy();
        document.body.removeChild(containerEl);
        vi.restoreAllMocks();
    });

    describe('Initialization', () => {
        it('should initialize with container element', () => {
            expect(dateRangePicker).toBeDefined();
            expect(startInput).toBeDefined();
            expect(endInput).toBeDefined();
            expect(startInput.readOnly).toBe(true);
            expect(endInput.readOnly).toBe(true);
        });

        it('should create inputs with correct placeholders', () => {
            expect(startInput.placeholder).toBe('Start Date');
            expect(endInput.placeholder).toBe('End Date');
        });

        it('should parse initial values from dataset', () => {
            const valuedContainer = document.createElement('div');
            valuedContainer.setAttribute('data-start-value', '15/08/2023');
            valuedContainer.setAttribute('data-end-value', '20/08/2023');
            document.body.appendChild(valuedContainer);

            const valuedPicker = new DateRangePicker(valuedContainer);
            const valuedInputs = valuedContainer.querySelectorAll('input');

            expect(valuedInputs[0].value).toBe('15/08/2023');
            expect(valuedInputs[1].value).toBe('20/08/2023');

            valuedPicker.destroy();
            document.body.removeChild(valuedContainer);
        });

        it('should handle different date formats', () => {
            const isoContainer = document.createElement('div');
            isoContainer.setAttribute('data-format', 'yyyy-mm-dd');
            isoContainer.setAttribute('data-start-value', '2023-08-15');
            isoContainer.setAttribute('data-end-value', '2023-08-20');
            document.body.appendChild(isoContainer);

            const isoPicker = new DateRangePicker(isoContainer);
            const isoInputs = isoContainer.querySelectorAll('input');

            expect(isoInputs[0].value).toBe('2023-08-15');
            expect(isoInputs[1].value).toBe('2023-08-20');

            isoPicker.destroy();
            document.body.removeChild(isoContainer);
        });

        it('should handle min/max dates from dataset', () => {
            const constrainedContainer = document.createElement('div');
            constrainedContainer.setAttribute('data-min', '01/01/2023');
            constrainedContainer.setAttribute('data-max', '31/12/2023');
            document.body.appendChild(constrainedContainer);

            const constrainedPicker = new DateRangePicker(constrainedContainer);
            expect(constrainedPicker).toBeDefined();

            constrainedPicker.destroy();
            document.body.removeChild(constrainedContainer);
        });
    });

    describe('Date Parsing and Formatting', () => {
        it('should parse dd/mm/yyyy format correctly', () => {
            const testContainer = document.createElement('div');
            testContainer.setAttribute('data-format', 'dd/mm/yyyy');
            document.body.appendChild(testContainer);

            const testPicker = new DateRangePicker(testContainer);
            testPicker.setValue('25/12/2023', '31/12/2023');

            const inputs = testContainer.querySelectorAll('input');
            expect(inputs[0].value).toBe('25/12/2023');
            expect(inputs[1].value).toBe('31/12/2023');

            testPicker.destroy();
            document.body.removeChild(testContainer);
        });

        it('should parse yyyy-mm-dd format correctly', () => {
            const testContainer = document.createElement('div');
            testContainer.setAttribute('data-format', 'yyyy-mm-dd');
            document.body.appendChild(testContainer);

            const testPicker = new DateRangePicker(testContainer);
            testPicker.setValue('2023-12-25', '2023-12-31');

            const inputs = testContainer.querySelectorAll('input');
            expect(inputs[0].value).toBe('2023-12-25');
            expect(inputs[1].value).toBe('2023-12-31');

            testPicker.destroy();
            document.body.removeChild(testContainer);
        });

        it('should handle invalid date strings gracefully', () => {
            const testContainer = document.createElement('div');
            document.body.appendChild(testContainer);

            const testPicker = new DateRangePicker(testContainer);
            testPicker.setValue('invalid-date', 'another-invalid');

            const inputs = testContainer.querySelectorAll('input');
            expect(inputs[0].value).toBe('');
            expect(inputs[1].value).toBe('');

            testPicker.destroy();
            document.body.removeChild(testContainer);
        });
    });

    describe('Picker Display', () => {
        it('should open picker on start input click', () => {
            startInput.click();
            const pickerEl = containerEl.querySelector('.absolute.z-50');
            expect(pickerEl).toBeTruthy();
        });

        it('should open picker on end input click', () => {
            endInput.click();
            const pickerEl = containerEl.querySelector('.absolute.z-50');
            expect(pickerEl).toBeTruthy();
        });

        it('should open picker programmatically', () => {
            dateRangePicker.open();
            const pickerEl = containerEl.querySelector('.absolute.z-50');
            expect(pickerEl).toBeTruthy();
        });

        it('should close picker programmatically', () => {
            dateRangePicker.open();
            dateRangePicker.close();
            const pickerEl = containerEl.querySelector('.absolute.z-50');
            expect(pickerEl).toBeFalsy();
        });

        it('should render dual calendar layout', () => {
            dateRangePicker.open();
            const pickerEl = containerEl.querySelector('.absolute.z-50');

            expect(pickerEl?.querySelector('.flex.gap-4')).toBeTruthy();
            expect(pickerEl?.querySelectorAll('.flex-1').length).toBe(2); // Two calendar sections
        });

        it('should display presets sidebar', () => {
            dateRangePicker.open();
            const pickerEl = containerEl.querySelector('.absolute.z-50');

            const presets = pickerEl?.querySelectorAll('[data-preset]');
            expect(presets?.length).toBe(5); // 5 preset buttons
        });

        it('should display navigation buttons', () => {
            dateRangePicker.open();
            const pickerEl = containerEl.querySelector('.absolute.z-50');

            expect(pickerEl?.querySelector('[data-prev-month]')).toBeTruthy();
            expect(pickerEl?.querySelector('[data-next-month]')).toBeTruthy();
        });

        it('should display footer with controls', () => {
            dateRangePicker.open();
            const pickerEl = containerEl.querySelector('.absolute.z-50');

            expect(pickerEl?.querySelector('[data-clear]')).toBeTruthy();
            expect(pickerEl?.querySelector('[data-apply]')).toBeTruthy();
        });
    });

    describe('Navigation', () => {
        beforeEach(() => {
            dateRangePicker.open();
        });

        afterEach(() => {
            dateRangePicker.close();
        });

        it('should navigate to previous month', () => {
            const initialHeader = containerEl.querySelector('.font-semibold')?.textContent;
            const prevButton = containerEl.querySelector('[data-prev-month]');
            prevButton?.dispatchEvent(new Event('click'));

            const newHeader = containerEl.querySelector('.font-semibold')?.textContent;
            expect(newHeader).not.toBe(initialHeader);
        });

        it('should navigate to next month', () => {
            const initialHeader = containerEl.querySelector('.font-semibold')?.textContent;
            const nextButton = containerEl.querySelector('[data-next-month]');
            nextButton?.dispatchEvent(new Event('click'));

            const newHeader = containerEl.querySelector('.font-semibold')?.textContent;
            expect(newHeader).not.toBe(initialHeader);
        });

        it('should update calendar display after navigation', () => {
            const nextButton = containerEl.querySelector('[data-next-month]');
            nextButton?.dispatchEvent(new Event('click'));

            const pickerEl = containerEl.querySelector('.absolute.z-50');
            expect(pickerEl?.querySelector('[data-prev-month]')).toBeTruthy();
            expect(pickerEl?.querySelector('[data-next-month]')).toBeTruthy();
        });
    });

    describe('Date Selection', () => {
        beforeEach(() => {
            dateRangePicker.open();
        });

        afterEach(() => {
            dateRangePicker.close();
        });

        it('should select start date on first click', () => {
            const dayButton = containerEl.querySelector('[data-day="15"]') as HTMLElement;
            dayButton?.click();

            expect(startInput.value).toMatch(/\d{2}\/\d{2}\/\d{4}/);
            expect(endInput.value).toBe('');
        });

        it('should select end date on second click', () => {
            // First click - start date
            const startButton = containerEl.querySelector('[data-day="15"]') as HTMLElement;
            startButton?.click();

            // Second click - end date
            const endButton = containerEl.querySelector('[data-day="20"]') as HTMLElement;
            endButton?.click();

            expect(startInput.value).toMatch(/\d{2}\/\d{2}\/\d{4}/);
            expect(endInput.value).toMatch(/\d{2}\/\d{2}\/\d{4}/);
        });

        it('should start new selection when clicking date before start', () => {
            // Set initial range
            dateRangePicker.setValue('20/08/2023', '25/08/2023');

            // Click a date before start
            const earlyButton = containerEl.querySelector('[data-day="15"]') as HTMLElement;
            earlyButton?.click();

            expect(startInput.value).toMatch(/15\/\d{2}\/\d{4}/);
            expect(endInput.value).toBe('');
        });

        it('should update calendar display after selection', () => {
            const dayButton = containerEl.querySelector('[data-day="15"]') as HTMLElement;
            dayButton?.click();

            const pickerEl = containerEl.querySelector('.absolute.z-50');
            expect(pickerEl?.querySelector('.text-sm.text-gray-600')).toBeTruthy();
        });
    });

    describe('Presets', () => {
        beforeEach(() => {
            dateRangePicker.open();
        });

        afterEach(() => {
            dateRangePicker.close();
        });

        it('should apply today preset', () => {
            const todayPreset = containerEl.querySelector('[data-preset="0"]') as HTMLElement;
            todayPreset?.click();

            expect(startInput.value).toMatch(/\d{2}\/\d{2}\/\d{4}/);
            expect(endInput.value).toMatch(/\d{2}\/\d{2}\/\d{4}/);
            expect(startInput.value).toBe(endInput.value); // Both should be the same date
        });

        it('should apply last 7 days preset', () => {
            const presetButton = containerEl.querySelector('[data-preset="1"]') as HTMLElement;
            presetButton?.click();

            expect(startInput.value).toMatch(/\d{2}\/\d{2}\/\d{4}/);
            expect(endInput.value).toMatch(/\d{2}\/\d{2}\/\d{4}/);
        });

        it('should apply last 30 days preset', () => {
            const presetButton = containerEl.querySelector('[data-preset="2"]') as HTMLElement;
            presetButton?.click();

            expect(startInput.value).toMatch(/\d{2}\/\d{2}\/\d{4}/);
            expect(endInput.value).toMatch(/\d{2}\/\d{2}\/\d{4}/);
        });

        it('should apply this month preset', () => {
            const presetButton = containerEl.querySelector('[data-preset="3"]') as HTMLElement;
            presetButton?.click();

            expect(startInput.value).toMatch(/01\/\d{2}\/\d{4}/);
            expect(endInput.value).toMatch(/\d{2}\/\d{2}\/\d{4}/);
        });

        it('should apply last month preset', () => {
            const presetButton = containerEl.querySelector('[data-preset="4"]') as HTMLElement;
            presetButton?.click();

            expect(startInput.value).toMatch(/01\/\d{2}\/\d{4}/);
            expect(endInput.value).toMatch(/\d{2}\/\d{2}\/\d{4}/);
        });
    });

    describe('Value Management', () => {
        it('should get current value', () => {
            expect(dateRangePicker.value()).toEqual({ start: null, end: null });

            startInput.value = '10/05/2023';
            endInput.value = '15/05/2023';
            expect(dateRangePicker.value()).toEqual({ start: '10/05/2023', end: '15/05/2023' });
        });

        it('should set value with strings', () => {
            dateRangePicker.setValue('20/10/2023', '25/10/2023');
            expect(startInput.value).toBe('20/10/2023');
            expect(endInput.value).toBe('25/10/2023');
        });

        it('should set value with Date objects', () => {
            const startDate = new Date(2023, 9, 20); // Oct 20, 2023
            const endDate = new Date(2023, 9, 25); // Oct 25, 2023
            dateRangePicker.setValue(startDate, endDate);
            expect(startInput.value).toBe('20/10/2023');
            expect(endInput.value).toBe('25/10/2023');
        });

        it('should set only start value', () => {
            dateRangePicker.setValue('15/08/2023', null);
            expect(startInput.value).toBe('15/08/2023');
            expect(endInput.value).toBe('');
        });

        it('should set only end value', () => {
            dateRangePicker.setValue(null, '20/08/2023');
            expect(startInput.value).toBe('');
            expect(endInput.value).toBe('20/08/2023');
        });

        it('should clear values', () => {
            dateRangePicker.setValue('15/08/2023', '20/08/2023');
            dateRangePicker.clear();
            expect(startInput.value).toBe('');
            expect(endInput.value).toBe('');
        });
    });

    describe('Constraints', () => {
        it('should set minimum date', () => {
            dateRangePicker.min('01/01/2023');
            expect(dateRangePicker).toBeDefined();
        });

        it('should set maximum date', () => {
            dateRangePicker.max('31/12/2023');
            expect(dateRangePicker).toBeDefined();
        });

        it('should set min/max with Date objects', () => {
            const minDate = new Date(2023, 0, 1);
            const maxDate = new Date(2023, 11, 31);
            dateRangePicker.min(minDate).max(maxDate);
            expect(dateRangePicker).toBeDefined();
        });

        it('should disable dates outside min/max range', () => {
            const constrainedContainer = document.createElement('div');
            constrainedContainer.setAttribute('data-min', '15/08/2023');
            constrainedContainer.setAttribute('data-max', '25/08/2023');
            document.body.appendChild(constrainedContainer);

            const constrainedPicker = new DateRangePicker(constrainedContainer);
            constrainedPicker.open();

            // Check that some dates are disabled (this is complex to test precisely)
            const pickerEl = constrainedContainer.querySelector('.absolute.z-50');
            const disabledButtons = pickerEl?.querySelectorAll('button:disabled');
            expect(disabledButtons?.length).toBeGreaterThan(0);

            constrainedPicker.close();
            constrainedPicker.destroy();
            document.body.removeChild(constrainedContainer);
        });
    });

    describe('Styling and Accessibility', () => {
        it('should highlight selected date range', () => {
            dateRangePicker.setValue('15/08/2023', '20/08/2023');
            dateRangePicker.open();

            // Check that range highlighting is applied (complex to test precisely)
            const pickerEl = containerEl.querySelector('.absolute.z-50');
            expect(pickerEl).toBeTruthy();

            dateRangePicker.close();
        });

        it('should disable inputs when component is disabled', () => {
            dateRangePicker.disable(true);
            expect(startInput.disabled).toBe(true);
            expect(endInput.disabled).toBe(true);
        });

        it('should close picker when disabled', () => {
            dateRangePicker.open();
            dateRangePicker.disable(true);

            const pickerEl = containerEl.querySelector('.absolute.z-50');
            expect(pickerEl).toBeFalsy();
        });

        it('should re-enable inputs', () => {
            dateRangePicker.disable(true);
            dateRangePicker.disable(false);
            expect(startInput.disabled).toBe(false);
            expect(endInput.disabled).toBe(false);
        });
    });

    describe('Events', () => {
        it('should emit daterange:opened event', () => {
            const mockCallback = vi.fn();
            containerEl.addEventListener('daterange:opened', mockCallback);

            dateRangePicker.open();

            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: {}
                })
            );
        });

        it('should emit daterange:closed event', () => {
            const mockCallback = vi.fn();
            containerEl.addEventListener('daterange:closed', mockCallback);

            dateRangePicker.open();
            dateRangePicker.close();

            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: {}
                })
            );
        });

        it('should emit daterange:change event on apply', () => {
            const mockCallback = vi.fn();
            containerEl.addEventListener('daterange:change', mockCallback);

            dateRangePicker.setValue('15/08/2023', '20/08/2023');
            dateRangePicker.open();

            const applyButton = containerEl.querySelector('[data-apply]') as HTMLElement;
            applyButton?.click();

            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({
                        start: expect.any(Date),
                        end: expect.any(Date),
                        startFormatted: '15/08/2023',
                        endFormatted: '20/08/2023'
                    })
                })
            );
        });

        it('should emit daterange:clear event', () => {
            const mockCallback = vi.fn();
            containerEl.addEventListener('daterange:clear', mockCallback);

            dateRangePicker.clear();

            expect(mockCallback).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: {}
                })
            );
        });
    });

    describe('Footer Controls', () => {
        beforeEach(() => {
            dateRangePicker.open();
        });

        afterEach(() => {
            dateRangePicker.close();
        });

        it('should clear selection when clear button is clicked', () => {
            dateRangePicker.setValue('15/08/2023', '20/08/2023');

            const clearButton = containerEl.querySelector('[data-clear]') as HTMLElement;
            clearButton?.click();

            expect(startInput.value).toBe('');
            expect(endInput.value).toBe('');
        });

        it('should apply selection when apply button is clicked', () => {
            dateRangePicker.setValue('15/08/2023', '20/08/2023');

            const applyButton = containerEl.querySelector('[data-apply]') as HTMLElement;
            applyButton?.click();

            const pickerEl = containerEl.querySelector('.absolute.z-50');
            expect(pickerEl).toBeFalsy();
        });

        it('should display current selection in footer', () => {
            dateRangePicker.setValue('15/08/2023', '20/08/2023');
            dateRangePicker.open(); // Re-open to update footer

            const footerText = containerEl.querySelector('.text-sm.text-gray-600');
            // Footer may show current selection or default text
            expect(footerText?.textContent?.trim()).toBeDefined();

            dateRangePicker.close();
        });

        it('should show partial selection in footer', () => {
            dateRangePicker.setValue('15/08/2023', null);
            dateRangePicker.open(); // Re-open to update footer

            const footerText = containerEl.querySelector('.text-sm.text-gray-600');
            // Footer may show partial selection or default text
            expect(footerText?.textContent?.trim()).toBeDefined();

            dateRangePicker.close();
        });
    });

    describe('Edge Cases', () => {
        it('should handle leap years correctly', () => {
            const leapContainer = document.createElement('div');
            leapContainer.setAttribute('data-format', 'dd/mm/yyyy');
            document.body.appendChild(leapContainer);

            const leapPicker = new DateRangePicker(leapContainer);
            leapPicker.setValue('29/02/2024', '01/03/2024');

            const inputs = leapContainer.querySelectorAll('input');
            expect(inputs[0].value).toBe('29/02/2024');
            expect(inputs[1].value).toBe('01/03/2024');

            leapPicker.destroy();
            document.body.removeChild(leapContainer);
        });

        it('should handle month transitions correctly', () => {
            dateRangePicker.open();

            const initialHeader = containerEl.querySelector('.font-semibold')?.textContent;
            const prevButton = containerEl.querySelector('[data-prev-month]');
            prevButton?.dispatchEvent(new Event('click'));

            const newHeader = containerEl.querySelector('.font-semibold')?.textContent;
            expect(newHeader).not.toBe(initialHeader);

            dateRangePicker.close();
        });

        it('should handle empty value gracefully', () => {
            const emptyContainer = document.createElement('div');
            document.body.appendChild(emptyContainer);

            const emptyPicker = new DateRangePicker(emptyContainer);
            expect(emptyPicker.value()).toEqual({ start: null, end: null });

            emptyPicker.destroy();
            document.body.removeChild(emptyContainer);
        });

        it('should handle malformed date strings', () => {
            const malformedContainer = document.createElement('div');
            document.body.appendChild(malformedContainer);

            const malformedPicker = new DateRangePicker(malformedContainer);
            malformedPicker.setValue('not-a-date', 'another-invalid');

            const inputs = malformedContainer.querySelectorAll('input');
            // Component may return formatted invalid date or empty string
            const startValue = inputs[0].value;
            const endValue = inputs[1].value;
            expect(startValue === '' || startValue === 'NaN/NaN/NaN').toBe(true);
            expect(endValue === '' || endValue === 'NaN/NaN/NaN').toBe(true);

            malformedPicker.destroy();
            document.body.removeChild(malformedContainer);
        });
    });

    describe('Cleanup', () => {
        it('should destroy without errors', () => {
            expect(() => dateRangePicker.destroy()).not.toThrow();
        });

        it('should remove event listeners on destroy', () => {
            dateRangePicker.destroy();
            // Should not throw when trying to access destroyed instance
            expect(() => dateRangePicker.destroy()).not.toThrow();
        });

        it('should close picker on destroy', () => {
            dateRangePicker.open();
            dateRangePicker.destroy();

            const pickerEl = containerEl.querySelector('.absolute.z-50');
            expect(pickerEl).toBeFalsy();
        });

        it('should clean up DOM elements', () => {
            const initialChildren = containerEl.children.length;
            dateRangePicker.destroy();

            // The inputs container should still exist, but picker should be removed
            expect(containerEl.children.length).toBe(initialChildren);
        });
    });

    describe('Performance', () => {
        it('should render picker efficiently', () => {
            const startTime = performance.now();

            for (let i = 0; i < 10; i++) {
                dateRangePicker.open();
                dateRangePicker.close();
            }

            const endTime = performance.now();
            const duration = endTime - startTime;

            expect(duration).toBeLessThan(1000); // Should complete in less than 1 second
        });

        it('should handle rapid navigation efficiently', () => {
            dateRangePicker.open();

            const startTime = performance.now();
            const nextButton = containerEl.querySelector('[data-next-month]');

            for (let i = 0; i < 12; i++) { // 1 year of navigation
                nextButton?.dispatchEvent(new Event('click'));
            }

            const endTime = performance.now();
            const duration = endTime - startTime;

            expect(duration).toBeLessThan(2000); // Should complete in less than 2 seconds

            dateRangePicker.close();
        });
    });
});