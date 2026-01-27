import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest';
import { Table } from '../components/Table';
import { instances } from '../core/registry';

describe('Table Component', () => {
    let container: HTMLElement;
    let tableElement: HTMLTableElement;
    let table: Table;

    beforeEach(() => {
        container = document.createElement('div');
        document.body.appendChild(container);

        tableElement = document.createElement('table');
        tableElement.innerHTML = `
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>John Doe</td>
                    <td>john@example.com</td>
                    <td>Active</td>
                </tr>
            </tbody>
        `;
        container.appendChild(tableElement);

        table = new Table(tableElement);
    });

    afterEach(() => {
        table.destroy();
        document.body.removeChild(container);
    });

    describe('Initialization', () => {
        it('should initialize with table element', () => {
            expect(table).toBeInstanceOf(Table);
            expect(instances.get(tableElement)).toBe(table);
        });

        it('should find tbody element', () => {
            expect(tableElement.querySelector('tbody')).not.toBeNull();
        });

        it('should handle table without tbody', () => {
            const tableWithoutTbody = document.createElement('table');
            tableWithoutTbody.innerHTML = '<thead><tr><th>Test</th></tr></thead>';
            container.appendChild(tableWithoutTbody);

            const tableInstance = new Table(tableWithoutTbody);
            expect(tableInstance).toBeInstanceOf(Table);

            tableInstance.destroy();
            container.removeChild(tableWithoutTbody);
        });
    });

    describe('Functionality', () => {
        it('loading() should show loading state', () => {
            table.loading(true);

            const tbody = tableElement.querySelector('tbody');
            expect(tbody).not.toBeNull();
            expect(tbody!.innerHTML).toContain('Carregando');
            expect(tbody!.innerHTML).toContain('animate-spin');
            expect(tableElement.getAttribute('aria-busy')).toBe('true');
        });

        it('loading() should hide loading state', () => {
            table.loading(true);
            table.loading(false);

            expect(tableElement.getAttribute('aria-busy')).toBeNull();
        });

        it('loading() should default to true', () => {
            table.loading();

            expect(tableElement.getAttribute('aria-busy')).toBe('true');
        });

        it('html() should set tbody content', () => {
            const newRows = `
                <tr>
                    <td>Jane Doe</td>
                    <td>jane@example.com</td>
                    <td>Inactive</td>
                </tr>
                <tr>
                    <td>Bob Smith</td>
                    <td>bob@example.com</td>
                    <td>Active</td>
                </tr>
            `;

            table.html(newRows);

            const tbody = tableElement.querySelector('tbody');
            expect(tbody!.innerHTML).toBe(newRows);
            expect(tableElement.getAttribute('aria-busy')).toBeNull();
        });

        it('empty() should show empty message', () => {
            table.empty();

            const tbody = tableElement.querySelector('tbody');
            expect(tbody!.innerHTML).toContain('Nenhum registro encontrado');
            expect(tbody!.innerHTML).toContain('py-12');
            expect(tbody!.innerHTML).toContain('text-center');
        });

        it('empty() should accept custom message', () => {
            const customMessage = 'No data available';
            table.empty(customMessage);

            const tbody = tableElement.querySelector('tbody');
            expect(tbody!.innerHTML).toContain(customMessage);
        });

        it('should return this for method chaining', () => {
            expect(table.loading()).toBe(table);
            expect(table.html('<tr><td>test</td></tr>')).toBe(table);
            expect(table.empty()).toBe(table);
        });

        it('should handle operations on table without tbody', () => {
            const tableWithoutTbody = document.createElement('table');
            container.appendChild(tableWithoutTbody);

            const tableInstance = new Table(tableWithoutTbody);

            // These should not throw errors
            expect(() => tableInstance.loading(true)).not.toThrow();
            expect(() => tableInstance.html('<tr><td>test</td></tr>')).not.toThrow();
            expect(() => tableInstance.empty()).not.toThrow();

            tableInstance.destroy();
            container.removeChild(tableWithoutTbody);
        });
    });

    describe('Events', () => {
        it('should emit table:loading on loading true', () => {
            const mockEmit = vi.fn();
            tableElement.addEventListener('table:loading', mockEmit);

            table.loading(true);

            expect(mockEmit).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({ loading: true })
                })
            );
        });

        it('should emit table:loading on loading false', () => {
            const mockEmit = vi.fn();
            tableElement.addEventListener('table:loading', mockEmit);

            table.loading(false);

            expect(mockEmit).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({ loading: false })
                })
            );
        });

        it('should emit table:updated on html', () => {
            const mockEmit = vi.fn();
            tableElement.addEventListener('table:updated', mockEmit);

            table.html('<tr><td>test</td></tr>');

            expect(mockEmit).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({})
                })
            );
        });

        it('should emit table:empty on empty', () => {
            const mockEmit = vi.fn();
            tableElement.addEventListener('table:empty', mockEmit);

            table.empty('Custom message');

            expect(mockEmit).toHaveBeenCalledWith(
                expect.objectContaining({
                    detail: expect.objectContaining({ message: 'Custom message' })
                })
            );
        });
    });

    describe('Accessibility', () => {
        it('should set aria-busy on loading', () => {
            expect(tableElement.getAttribute('aria-busy')).toBeNull();

            table.loading(true);
            expect(tableElement.getAttribute('aria-busy')).toBe('true');

            table.loading(false);
            expect(tableElement.getAttribute('aria-busy')).toBeNull();
        });

        it('should remove aria-busy on html update', () => {
            table.loading(true);
            expect(tableElement.getAttribute('aria-busy')).toBe('true');

            table.html('<tr><td>test</td></tr>');
            expect(tableElement.getAttribute('aria-busy')).toBeNull();
        });
    });

    describe('HTML Content', () => {
        it('loading state should have proper structure', () => {
            table.loading(true);

            const tbody = tableElement.querySelector('tbody');
            const loadingCell = tbody!.querySelector('td');

            expect(loadingCell).not.toBeNull();
            expect(loadingCell!.getAttribute('colspan')).toBe('99');
            expect(loadingCell!.innerHTML).toContain('animate-spin');
            expect(loadingCell!.innerHTML).toContain('Carregando');
        });

        it('empty state should have proper structure', () => {
            table.empty();

            const tbody = tableElement.querySelector('tbody');
            const emptyCell = tbody!.querySelector('td');

            expect(emptyCell).not.toBeNull();
            expect(emptyCell!.getAttribute('colspan')).toBe('99');
            expect(emptyCell!.className).toContain('py-12');
            expect(emptyCell!.className).toContain('text-center');
            expect(emptyCell!.className).toContain('text-gray-500');
        });

        it('should preserve thead when updating tbody', () => {
            const originalThead = tableElement.querySelector('thead');
            expect(originalThead).not.toBeNull();

            table.html('<tr><td>New content</td></tr>');

            const updatedThead = tableElement.querySelector('thead');
            expect(updatedThead).toBe(originalThead);
        });
    });

    describe('Edge Cases', () => {
        it('should handle empty html string', () => {
            table.html('');

            const tbody = tableElement.querySelector('tbody');
            expect(tbody!.innerHTML).toBe('');
        });

        it('should handle html with multiple rows', () => {
            const multipleRows = `
                <tr><td>Row 1</td></tr>
                <tr><td>Row 2</td></tr>
                <tr><td>Row 3</td></tr>
            `;

            table.html(multipleRows);

            const rows = tableElement.querySelectorAll('tbody tr');
            expect(rows).toHaveLength(3);
        });

        it('should handle special characters in empty message', () => {
            const specialMessage = 'No data found with émojis 🚀';
            table.empty(specialMessage);

            const tbody = tableElement.querySelector('tbody');
            const cell = tbody!.querySelector('td');
            expect(cell!.textContent).toBe(specialMessage);
        });

        it('should handle loading state multiple times', () => {
            table.loading(true);
            expect(tableElement.getAttribute('aria-busy')).toBe('true');

            table.loading(true); // Again
            expect(tableElement.getAttribute('aria-busy')).toBe('true');

            table.loading(false);
            expect(tableElement.getAttribute('aria-busy')).toBeNull();
        });
    });

    describe('Cleanup', () => {
        it('should remove from instances on destroy', () => {
            expect(instances.get(tableElement)).toBe(table);
            table.destroy();
            expect(instances.get(tableElement)).toBeUndefined();
        });
    });
});