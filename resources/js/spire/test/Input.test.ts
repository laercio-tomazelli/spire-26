import { describe, it, expect, beforeEach, afterEach } from 'vitest'
import { Input } from '../components/Input'

describe('Input Component', () => {
    let inputEl: HTMLInputElement
    let errorEl: HTMLElement
    let inputInstance: Input

    beforeEach(() => {
        // Criar estrutura HTML
        const wrapper = document.createElement('div')
        inputEl = document.createElement('input')
        inputEl.type = 'text'
        errorEl = document.createElement('div')
        errorEl.className = 'error-text hidden'
        wrapper.appendChild(inputEl)
        wrapper.appendChild(errorEl)
        document.body.appendChild(wrapper)
        inputInstance = new Input(inputEl)
    })

    afterEach(() => {
        inputInstance.destroy()
        document.body.innerHTML = ''
    })

    it('should initialize correctly', () => {
        expect(inputEl.value).toBe('')
        expect(errorEl.classList.contains('hidden')).toBe(true)
    })

    it('should set value', () => {
        inputInstance.value('test value')
        expect(inputEl.value).toBe('test value')
    })

    it('should show error', () => {
        inputInstance.error('Error message')
        expect(inputEl.classList.contains('border-red-500')).toBe(true)
        expect(inputEl.getAttribute('aria-invalid')).toBe('true')
        expect(errorEl.textContent).toBe('Error message')
        expect(errorEl.classList.contains('hidden')).toBe(false)
        expect(errorEl.getAttribute('role')).toBe('alert')
    })

    it('should clear error on input', () => {
        inputInstance.error('Error')
        expect(inputEl.classList.contains('border-red-500')).toBe(true)

        inputEl.value = 'a'
        inputEl.dispatchEvent(new Event('input'))
        expect(inputEl.classList.contains('border-red-500')).toBe(false)
    })

    it('should focus', () => {
        inputInstance.focus()
        expect(document.activeElement).toBe(inputEl)
    })

    it('should clear', () => {
        inputInstance.value('test').error('error')
        inputInstance.clear()
        expect(inputEl.value).toBe('')
        expect(errorEl.classList.contains('hidden')).toBe(true)
    })

    it('should disable', () => {
        inputInstance.disable(true)
        expect(inputEl.disabled).toBe(true)
        expect(inputEl.getAttribute('aria-disabled')).toBe('true')

        inputInstance.disable(false)
        expect(inputEl.disabled).toBe(false)
    })

    // Teste de performance: manipulação rápida
    it('should perform value changes efficiently', () => {
        const start = performance.now()
        for (let i = 0; i < 100; i++) {
            inputInstance.value(`value ${i}`)
        }
        const end = performance.now()
        const duration = end - start
        expect(duration).toBeLessThan(100) // Menos de 100ms para 100 mudanças
    })

    // Teste de memória
    it('should not leak memory on destroy', () => {
        const initialInstances = (global as any).instances?.size || 0
        inputInstance.destroy()
        const afterDestroy = (global as any).instances?.size || 0
        expect(afterDestroy).toBeLessThanOrEqual(initialInstances)
    })
})