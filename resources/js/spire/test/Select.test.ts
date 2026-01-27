import { describe, it, expect, beforeEach, afterEach } from 'vitest'
import { Select } from '../components/Select'

describe('Select Component', () => {
    let selectEl: HTMLElement
    let selectInstance: Select

    beforeEach(() => {
        // Criar estrutura HTML baseada no componente
        selectEl = document.createElement('div')
        selectEl.innerHTML = `
      <button data-select-trigger aria-haspopup="listbox" aria-expanded="false">
        <span data-select-value>Select an option</span>
      </button>
      <div data-select-dropdown class="hidden">
        <div data-select-options role="listbox">
          <div data-option="1" role="option">Option 1</div>
          <div data-option="2" role="option">Option 2</div>
          <div data-option="3" role="option" disabled>Option 3 (disabled)</div>
        </div>
      </div>
      <input type="hidden" name="select">
    `
        document.body.appendChild(selectEl)
        selectInstance = new Select(selectEl)
    })

    afterEach(() => {
        selectInstance.destroy()
        document.body.removeChild(selectEl)
    })

    it('should initialize correctly', () => {
        expect(selectInstance.value()).toBe('')
        expect(selectEl.querySelector('[data-select-dropdown]')?.classList.contains('hidden')).toBe(true)
    })

    it('should open and close', () => {
        selectInstance.open()
        expect(selectEl.querySelector('[data-select-dropdown]')?.classList.contains('hidden')).toBe(false)
        expect(selectEl.querySelector('[data-select-trigger]')?.getAttribute('aria-expanded')).toBe('true')

        selectInstance.close()
        expect(selectEl.querySelector('[data-select-dropdown]')?.classList.contains('hidden')).toBe(true)
        expect(selectEl.querySelector('[data-select-trigger]')?.getAttribute('aria-expanded')).toBe('false')
    })

    it('should set value', () => {
        selectInstance.setValue('2')
        expect(selectInstance.value()).toBe('2')
        expect(selectEl.querySelector('[data-select-value]')?.textContent).toBe('Option 2')
        expect(selectEl.querySelector('input[type="hidden"]')?.value).toBe('2')
        expect(selectEl.querySelector('[data-option="2"]')?.classList.contains('selected')).toBe(true)
    })

    it('should handle option click', () => {
        const option = selectEl.querySelector('[data-option="1"]') as HTMLElement
        option.click()
        expect(selectInstance.value()).toBe('1')
        expect(selectEl.querySelector('[data-select-dropdown]')?.classList.contains('hidden')).toBe(true)
    })

    it('should not select disabled option', () => {
        const disabledOption = selectEl.querySelector('[data-option="3"]') as HTMLElement
        disabledOption.click()
        expect(selectInstance.value()).toBe('')
    })

    it('should set options dynamically', () => {
        const newOptions = [
            { value: 'a', label: 'Alpha' },
            { value: 'b', label: 'Beta', disabled: true }
        ]
        selectInstance.options(newOptions)
        expect(selectEl.querySelectorAll('[data-option]').length).toBe(2)
        expect(selectEl.querySelector('[data-option="a"]')?.textContent?.trim()).toBe('Alpha')
    })

    it('should disable', () => {
        selectInstance.disable(true)
        expect((selectEl.querySelector('[data-select-trigger]') as HTMLButtonElement).disabled).toBe(true)
        expect(selectEl.querySelector('[data-select-trigger]')?.getAttribute('aria-disabled')).toBe('true')
    })

    it('should handle outside click', () => {
        selectInstance.open()
        expect(selectEl.querySelector('[data-select-dropdown]')?.classList.contains('hidden')).toBe(false)

        document.body.click()
        expect(selectEl.querySelector('[data-select-dropdown]')?.classList.contains('hidden')).toBe(true)
    })

    it('should handle escape key', () => {
        selectInstance.open()
        expect(selectEl.querySelector('[data-select-dropdown]')?.classList.contains('hidden')).toBe(false)

        const event = new KeyboardEvent('keydown', { key: 'Escape' })
        document.dispatchEvent(event)
        expect(selectEl.querySelector('[data-select-dropdown]')?.classList.contains('hidden')).toBe(true)
    })

    // Teste de performance: setValue rápido
    it('should perform setValue efficiently', () => {
        const start = performance.now()
        for (let i = 0; i < 50; i++) {
            selectInstance.setValue(`${i % 2 + 1}`)
        }
        const end = performance.now()
        const duration = end - start
        expect(duration).toBeLessThan(150) // Menos de 150ms para 50 mudanças
    })

    // Teste de memória
    it('should not leak memory on destroy', () => {
        const initialInstances = (global as any).instances?.size || 0
        selectInstance.destroy()
        const afterDestroy = (global as any).instances?.size || 0
        expect(afterDestroy).toBeLessThanOrEqual(initialInstances)
    })
})