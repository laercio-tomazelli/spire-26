import { describe, it, expect, beforeEach, afterEach } from 'vitest'
import { Progress } from '../components/Progress'

describe('Progress Component', () => {
    let element: HTMLElement
    let progress: Progress

    beforeEach(() => {
        // Criar elemento de teste com estrutura básica
        element = document.createElement('div')
        element.innerHTML = `
            <div data-progress-bar></div>
            <div data-progress-label></div>
        `
        document.body.appendChild(element)
    })

    afterEach(() => {
        // Limpar
        if (progress) {
            progress.destroy()
        }
        if (element.parentNode) {
            element.parentNode.removeChild(element)
        }
    })

    describe('Initialization', () => {
        it('should initialize with default values', () => {
            progress = new Progress(element)
            expect(progress).toBeDefined()

            const bar = element.querySelector('[data-progress-bar]') as HTMLElement
            const label = element.querySelector('[data-progress-label]') as HTMLElement

            expect(bar.style.width).toBe('0%')
            expect(label.textContent).toBe('0%')
        })

        it('should initialize with custom value from data-value', () => {
            element.setAttribute('data-value', '50')
            progress = new Progress(element)

            const bar = element.querySelector('[data-progress-bar]') as HTMLElement
            const label = element.querySelector('[data-progress-label]') as HTMLElement

            expect(bar.style.width).toBe('50%')
            expect(label.textContent).toBe('50%')
        })

        it('should initialize with custom max from data-max', () => {
            element.setAttribute('data-max', '200')
            element.setAttribute('data-value', '50')
            progress = new Progress(element)

            const bar = element.querySelector('[data-progress-bar]') as HTMLElement
            const label = element.querySelector('[data-progress-label]') as HTMLElement

            expect(bar.style.width).toBe('25%') // 50/200 = 25%
            expect(label.textContent).toBe('25%')
        })

        it('should handle missing bar element gracefully', () => {
            element.innerHTML = '<div data-progress-label></div>'
            progress = new Progress(element)
            expect(progress).toBeDefined()
        })

        it('should handle missing label element gracefully', () => {
            element.innerHTML = '<div data-progress-bar></div>'
            progress = new Progress(element)
            expect(progress).toBeDefined()
        })
    })

    describe('Value Management', () => {
        beforeEach(() => {
            progress = new Progress(element)
        })

        it('should get current value', () => {
            expect(progress.value()).toBe(0)
        })

        it('should set value and return this for chaining', () => {
            const result = progress.value(75)
            expect(result).toBe(progress)
            expect(progress.value()).toBe(75)

            const bar = element.querySelector('[data-progress-bar]') as HTMLElement
            const label = element.querySelector('[data-progress-label]') as HTMLElement

            expect(bar.style.width).toBe('75%')
            expect(label.textContent).toBe('75%')
        })

        it('should clamp value to maximum', () => {
            progress.value(150)
            expect(progress.value()).toBe(100)

            const bar = element.querySelector('[data-progress-bar]') as HTMLElement
            expect(bar.style.width).toBe('100%')
        })

        it('should clamp value to minimum', () => {
            progress.value(-10)
            expect(progress.value()).toBe(0)

            const bar = element.querySelector('[data-progress-bar]') as HTMLElement
            expect(bar.style.width).toBe('0%')
        })

        it('should increment value', () => {
            progress.increment()
            expect(progress.value()).toBe(10)

            progress.increment(25)
            expect(progress.value()).toBe(35)
        })

        it('should decrement value', () => {
            progress.value(50)
            progress.decrement()
            expect(progress.value()).toBe(40)

            progress.decrement(20)
            expect(progress.value()).toBe(20)
        })

        it('should complete progress', () => {
            progress.complete()
            expect(progress.value()).toBe(100)

            const bar = element.querySelector('[data-progress-bar]') as HTMLElement
            expect(bar.style.width).toBe('100%')
        })

        it('should reset progress', () => {
            progress.value(75)
            progress.reset()
            expect(progress.value()).toBe(0)

            const bar = element.querySelector('[data-progress-bar]') as HTMLElement
            expect(bar.style.width).toBe('0%')
        })
    })

    describe('Indeterminate Mode', () => {
        beforeEach(() => {
            progress = new Progress(element)
        })

        it('should enable indeterminate mode', () => {
            progress.indeterminate(true)

            const bar = element.querySelector('[data-progress-bar]') as HTMLElement
            const label = element.querySelector('[data-progress-label]') as HTMLElement

            expect(bar.classList.contains('animate-pulse')).toBe(true)
            expect(bar.style.width).toBe('100%')
            expect(label.textContent).toBe('')
            expect(element.hasAttribute('aria-valuenow')).toBe(false)
        })

        it('should disable indeterminate mode', () => {
            progress.indeterminate(true)
            progress.indeterminate(false)

            const bar = element.querySelector('[data-progress-bar]') as HTMLElement
            expect(bar.classList.contains('animate-pulse')).toBe(false)
            expect(element.hasAttribute('aria-valuenow')).toBe(true)
        })

        it('should exit indeterminate mode when setting value', () => {
            progress.indeterminate(true)
            progress.value(50)

            const bar = element.querySelector('[data-progress-bar]') as HTMLElement
            expect(bar.classList.contains('animate-pulse')).toBe(false)
            expect(bar.style.width).toBe('50%')
        })
    })

    describe('Accessibility', () => {
        beforeEach(() => {
            progress = new Progress(element)
        })

        it('should set correct ARIA attributes', () => {
            expect(element.getAttribute('role')).toBe('progressbar')
            expect(element.getAttribute('aria-valuemin')).toBe('0')
            expect(element.getAttribute('aria-valuemax')).toBe('100')
            expect(element.getAttribute('aria-valuenow')).toBe('0')
        })

        it('should update aria-valuenow when value changes', () => {
            progress.value(75)
            expect(element.getAttribute('aria-valuenow')).toBe('75')
        })

        it('should remove aria-valuenow in indeterminate mode', () => {
            progress.indeterminate(true)
            expect(element.hasAttribute('aria-valuenow')).toBe(false)
        })

        it('should handle custom max value in ARIA', () => {
            const customElement = document.createElement('div')
            customElement.innerHTML = `
                <div data-progress-bar></div>
                <div data-progress-label></div>
            `
            customElement.setAttribute('data-max', '200')
            document.body.appendChild(customElement)

            const customProgress = new Progress(customElement)
            expect(customElement.getAttribute('aria-valuemax')).toBe('200')

            customProgress.destroy()
            document.body.removeChild(customElement)
        })
    })

    describe('Events', () => {
        beforeEach(() => {
            progress = new Progress(element)
        })

        it('should emit progress:change event when value changes', () => {
            let eventData: any = null
            element.addEventListener('progress:change', (e: any) => {
                eventData = e.detail
            })

            progress.value(50)

            expect(eventData).toEqual({ value: 50, percent: 50 })
        })

        it('should emit progress:complete event', () => {
            let completed = false
            element.addEventListener('progress:complete', () => {
                completed = true
            })

            progress.complete()
            expect(completed).toBe(true)
        })

        it('should emit progress:reset event', () => {
            progress.value(50)
            let reset = false
            element.addEventListener('progress:reset', () => {
                reset = true
            })

            progress.reset()
            expect(reset).toBe(true)
        })
    })

    describe('Edge Cases', () => {
        it('should handle decimal values', () => {
            progress = new Progress(element)
            progress.value(33.7)

            const label = element.querySelector('[data-progress-label]') as HTMLElement
            expect(label.textContent).toBe('34%') // Arredondado
        })

        it('should handle very small max values', () => {
            element.setAttribute('data-max', '1')
            element.setAttribute('data-value', '1')
            progress = new Progress(element)

            const bar = element.querySelector('[data-progress-bar]') as HTMLElement
            expect(bar.style.width).toBe('100%')
        })

        it('should handle zero max value gracefully', () => {
            element.setAttribute('data-max', '0')
            progress = new Progress(element)

            // Não deve quebrar, mas comportamento pode ser indefinido
            expect(progress).toBeDefined()
        })
    })

    describe('Cleanup', () => {
        beforeEach(() => {
            progress = new Progress(element)
        })

        it('should destroy without errors', () => {
            expect(() => progress.destroy()).not.toThrow()
        })
    })

    // Teste de performance
    it('should perform value updates efficiently', () => {
        progress = new Progress(element)
        const start = performance.now()

        // Executar múltiplas atualizações
        for (let i = 0; i < 100; i++) {
            progress.value(i)
        }

        const end = performance.now()
        const duration = end - start
        expect(duration).toBeLessThan(200) // Menos de 200ms para 100 atualizações
    })
})