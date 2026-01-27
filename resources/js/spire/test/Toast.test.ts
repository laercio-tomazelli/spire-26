import { describe, it, expect, beforeEach, afterEach } from 'vitest'
import { toast } from '../utilities/Toast'

describe('Toast Utility', () => {
    const waitForToast = async () => {
        ; (toast as any)._processQueue()
        // Tentar múltiplas vezes para garantir que o container seja criado
        for (let i = 0; i < 10; i++) {
            await new Promise(resolve => setTimeout(resolve, 1))
                ; (toast as any)._processQueue()
            if (document.querySelector('.fixed.top-5.right-5')) break
        }
    }

    beforeEach(() => {
        // Limpar toasts e remover container antes de cada teste
        toast.clear()
        const existingContainer = document.querySelector('.fixed.top-5.right-5')
        if (existingContainer) {
            existingContainer.remove()
        }
    })

    afterEach(() => {
        // Limpar após cada teste
        toast.clear()
    })

    it('should create success toast', async () => {
        toast.success('Success message', 100)
        await waitForToast()
        const container = document.querySelector('.fixed.top-5.right-5')
        expect(container).toBeTruthy()
        const toastEl = container?.querySelector('.bg-green-600')
        expect(toastEl).toBeTruthy()
        expect(toastEl?.textContent).toContain('Success message')
    })

    it('should create error toast', async () => {
        toast.error('Error message', 100)
        await waitForToast()
        const container = document.querySelector('.fixed.top-5.right-5')
        expect(container).toBeTruthy()
        const toastEl = container?.querySelector('.bg-red-600')
        expect(toastEl).toBeTruthy()
        expect(toastEl?.textContent).toContain('Error message')
    })

    it('should create info toast', async () => {
        toast.info('Info message', 100)
        await waitForToast()
        const container = document.querySelector('.fixed.top-5.right-5')
        expect(container).toBeTruthy()
        const toastEl = container?.querySelector('.bg-blue-600')
        expect(toastEl).toBeTruthy()
        expect(toastEl?.textContent).toContain('Info message')
    })

    it('should create warning toast', async () => {
        toast.warning('Warning message', 100)
        await waitForToast()
        const container = document.querySelector('.fixed.top-5.right-5')
        expect(container).toBeTruthy()
        const toastEl = container?.querySelector('.bg-yellow-500')
        expect(toastEl).toBeTruthy()
        expect(toastEl?.textContent).toContain('Warning message')
    })

    it('should handle close button', async () => {
        toast.success('Test', 1000) // Longo para testar close
        await waitForToast()
        const container = document.querySelector('.fixed.top-5.right-5')
        expect(container).toBeTruthy()
        const closeBtn = container?.querySelector('button[aria-label="Fechar notificação"]') as HTMLElement
        expect(closeBtn).toBeTruthy()

        closeBtn.click()
        await new Promise(resolve => setTimeout(resolve, 350)) // Aguardar animação
        const toastEl = container?.querySelector('.bg-green-600')
        expect(toastEl).toBeFalsy()
    })

    it('should respect max visible toasts', async () => {
        // Criar mais de 3 toasts
        for (let i = 0; i < 5; i++) {
            toast.info(`Toast ${i}`, 10000) // Longo
        }
        await waitForToast()
        const container = document.querySelector('.fixed.top-5.right-5')
        expect(container).toBeTruthy()
        const visibleToasts = container?.querySelectorAll('div[role="alert"]')
        expect(visibleToasts?.length).toBeLessThanOrEqual(3)
    })

    it('should auto-remove after duration', async () => {
        toast.success('Auto remove', 100)
        await waitForToast()
        const container = document.querySelector('.fixed.top-5.right-5')
        expect(container).toBeTruthy()
        let toastEl = container?.querySelector('.bg-green-600')
        expect(toastEl).toBeTruthy()

        await new Promise(resolve => setTimeout(resolve, 500))
        toastEl = container?.querySelector('.bg-green-600')
        expect(toastEl).toBeFalsy()
    })

    it('should clear all toasts', async () => {
        toast.success('Toast 1', 10000)
        toast.error('Toast 2', 10000)
        await waitForToast()
        const container = document.querySelector('.fixed.top-5.right-5')
        expect(container).toBeTruthy()
        expect(container?.children.length).toBeGreaterThan(0)

        toast.clear()
        expect(container?.children.length).toBe(0)
    })

    // Teste de performance: criação rápida de múltiplos toasts
    it('should perform toast creation efficiently', () => {
        const start = performance.now()
        for (let i = 0; i < 20; i++) {
            toast.info(`Toast ${i}`, 0) // Sem auto-remove
        }
        ; (toast as any)._processQueue()
        const end = performance.now()
        const duration = end - start
        expect(duration).toBeLessThan(200) // Menos de 200ms para 20 toasts
    })
})