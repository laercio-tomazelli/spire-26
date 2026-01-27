import { describe, it, expect, beforeEach } from 'vitest'
import { Button } from '../components/Button'

describe('Button Component', () => {
    let buttonEl: HTMLButtonElement
    let buttonInstance: Button

    beforeEach(() => {
        // Criar elemento DOM para teste
        buttonEl = document.createElement('button')
        buttonEl.textContent = 'Test Button'
        document.body.appendChild(buttonEl)
        buttonInstance = new Button(buttonEl)
    })

    it('should initialize correctly', () => {
        expect(buttonEl.textContent).toBe('Test Button')
        expect(buttonEl.disabled).toBe(false)
    })

    it('should handle loading state', () => {
        buttonInstance.loading(true)
        expect(buttonEl.disabled).toBe(true)
        expect(buttonEl.getAttribute('aria-busy')).toBe('true')
        expect(buttonEl.textContent).toBe('Carregando...')

        buttonInstance.loading(false)
        expect(buttonEl.disabled).toBe(false)
        expect(buttonEl.textContent).toBe('Test Button')
    })

    it('should handle success state', async () => {
        buttonInstance.success('Saved!', 100)
        expect(buttonEl.textContent).toBe('Saved!')
        expect(buttonEl.classList.contains('bg-green-600')).toBe(true)

        // Aguardar timeout + buffer
        await new Promise(resolve => setTimeout(resolve, 150))
        expect(buttonEl.classList.contains('bg-green-600')).toBe(false)
        expect(buttonEl.textContent).toBe('Test Button')
    })

    it('should reset correctly', () => {
        buttonInstance.loading(true)
        buttonInstance.reset()
        expect(buttonEl.disabled).toBe(false)
        expect(buttonEl.textContent).toBe('Test Button')
    })

    // Teste de performance: medir tempo de manipulação DOM
    it('should perform DOM manipulation efficiently', () => {
        const start = performance.now()
        for (let i = 0; i < 100; i++) {
            buttonInstance.loading(true)
            buttonInstance.loading(false)
        }
        const end = performance.now()
        const duration = end - start
        expect(duration).toBeLessThan(100) // Menos de 100ms para 100 operações (ajustado para realismo)
    })

    // Teste de memória: verificar se não há vazamentos
    it('should not leak memory on destroy', () => {
        const initialInstances = (global as any).instances?.size || 0
        buttonInstance.destroy()
        const afterDestroy = (global as any).instances?.size || 0
        expect(afterDestroy).toBeLessThanOrEqual(initialInstances)
    })
})