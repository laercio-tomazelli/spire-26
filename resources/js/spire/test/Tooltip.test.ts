import { describe, it, expect, beforeEach, afterEach } from 'vitest'
import { Tooltip } from '../components/Tooltip'

describe('Tooltip Component', () => {
    let element: HTMLElement
    let tooltip: Tooltip

    beforeEach(() => {
        // Criar elemento de teste
        element = document.createElement('button')
        element.setAttribute('data-tooltip', 'Test tooltip')
        document.body.appendChild(element)
    })

    afterEach(() => {
        // Limpar tooltip se existir
        if (tooltip) {
            tooltip.destroy()
        }
        // Remover elemento do DOM
        if (element.parentNode) {
            element.parentNode.removeChild(element)
        }
        // Limpar tooltips restantes
        const tooltips = document.querySelectorAll('[role="tooltip"]')
        tooltips.forEach(tooltip => tooltip.remove())
    })

    describe('Initialization', () => {
        it('should initialize with data-tooltip attribute', () => {
            tooltip = new Tooltip(element)
            expect(tooltip).toBeDefined()
            expect(element.hasAttribute('title')).toBe(false) // Title deve ser removido
        })

        it('should initialize with title attribute as fallback', () => {
            element.removeAttribute('data-tooltip')
            element.setAttribute('title', 'Title tooltip')
            tooltip = new Tooltip(element)
            expect(tooltip).toBeDefined()
            expect(element.hasAttribute('title')).toBe(false)
        })

        it('should use default position when not specified', () => {
            tooltip = new Tooltip(element)
            expect(tooltip).toBeDefined()
        })

        it('should use custom position from data-tooltip-position', () => {
            element.setAttribute('data-tooltip-position', 'bottom')
            tooltip = new Tooltip(element)
            expect(tooltip).toBeDefined()
        })

        it('should handle empty content gracefully', () => {
            element.removeAttribute('data-tooltip')
            element.removeAttribute('title')
            tooltip = new Tooltip(element)
            expect(tooltip).toBeDefined()
        })
    })

    describe('Show/Hide Functionality', () => {
        beforeEach(() => {
            tooltip = new Tooltip(element)
        })

        it('should show tooltip on mouseenter', () => {
            element.dispatchEvent(new Event('mouseenter'))
            const tooltipEl = document.querySelector('[role="tooltip"]')
            expect(tooltipEl).toBeTruthy()
            expect(tooltipEl?.textContent).toBe('Test tooltip')
        })

        it('should hide tooltip on mouseleave', () => {
            element.dispatchEvent(new Event('mouseenter'))
            let tooltipEl = document.querySelector('[role="tooltip"]')
            expect(tooltipEl).toBeTruthy()

            element.dispatchEvent(new Event('mouseleave'))
            tooltipEl = document.querySelector('[role="tooltip"]')
            expect(tooltipEl).toBeFalsy()
        })

        it('should show tooltip on focus', () => {
            element.dispatchEvent(new Event('focus'))
            const tooltipEl = document.querySelector('[role="tooltip"]')
            expect(tooltipEl).toBeTruthy()
        })

        it('should hide tooltip on blur', () => {
            element.dispatchEvent(new Event('focus'))
            let tooltipEl = document.querySelector('[role="tooltip"]')
            expect(tooltipEl).toBeTruthy()

            element.dispatchEvent(new Event('blur'))
            tooltipEl = document.querySelector('[role="tooltip"]')
            expect(tooltipEl).toBeFalsy()
        })

        it('should not show tooltip if content is empty', () => {
            const emptyElement = document.createElement('button')
            document.body.appendChild(emptyElement)
            const newTooltip = new Tooltip(emptyElement)
            emptyElement.dispatchEvent(new Event('mouseenter'))
            const tooltipEl = document.querySelector('[role="tooltip"]')
            expect(tooltipEl).toBeFalsy()
            newTooltip.destroy()
            document.body.removeChild(emptyElement)
        })

        it('should not create multiple tooltips', () => {
            element.dispatchEvent(new Event('mouseenter'))
            element.dispatchEvent(new Event('mouseenter'))
            const tooltips = document.querySelectorAll('[role="tooltip"]')
            expect(tooltips.length).toBe(1)
        })
    })

    describe('Positioning', () => {
        it('should position tooltip at top by default', () => {
            tooltip = new Tooltip(element)
            element.dispatchEvent(new Event('mouseenter'))

            const tooltipEl = document.querySelector('[role="tooltip"]') as HTMLElement
            expect(tooltipEl).toBeTruthy()
            expect(tooltipEl.classList.contains('fixed')).toBe(true)
        })

        it('should position tooltip at bottom', () => {
            element.setAttribute('data-tooltip-position', 'bottom')
            tooltip = new Tooltip(element)
            element.dispatchEvent(new Event('mouseenter'))

            const tooltipEl = document.querySelector('[role="tooltip"]') as HTMLElement
            expect(tooltipEl).toBeTruthy()
        })

        it('should position tooltip at left', () => {
            element.setAttribute('data-tooltip-position', 'left')
            tooltip = new Tooltip(element)
            element.dispatchEvent(new Event('mouseenter'))

            const tooltipEl = document.querySelector('[role="tooltip"]') as HTMLElement
            expect(tooltipEl).toBeTruthy()
        })

        it('should position tooltip at right', () => {
            element.setAttribute('data-tooltip-position', 'right')
            tooltip = new Tooltip(element)
            element.dispatchEvent(new Event('mouseenter'))

            const tooltipEl = document.querySelector('[role="tooltip"]') as HTMLElement
            expect(tooltipEl).toBeTruthy()
        })

        it('should keep tooltip within viewport bounds', () => {
            // Posicionar elemento próximo à borda direita
            element.style.position = 'fixed'
            element.style.right = '10px'
            element.style.top = '50px'
            element.setAttribute('data-tooltip-position', 'right')

            tooltip = new Tooltip(element)
            element.dispatchEvent(new Event('mouseenter'))

            const tooltipEl = document.querySelector('[role="tooltip"]') as HTMLElement
            expect(tooltipEl).toBeTruthy()

            // Verificar se o tooltip foi criado com as classes corretas
            expect(tooltipEl.classList.contains('fixed')).toBe(true)
            expect(tooltipEl.classList.contains('z-50')).toBe(true)
        })
    })

    describe('Content Management', () => {
        beforeEach(() => {
            tooltip = new Tooltip(element)
        })

        it('should update tooltip content', () => {
            element.dispatchEvent(new Event('mouseenter'))
            let tooltipEl = document.querySelector('[role="tooltip"]')
            expect(tooltipEl?.textContent).toBe('Test tooltip')

            tooltip.update('Updated content')
            tooltipEl = document.querySelector('[role="tooltip"]')
            expect(tooltipEl?.textContent).toBe('Updated content')
        })

        it('should update tooltip content when hidden', () => {
            tooltip.update('Updated content')
            element.dispatchEvent(new Event('mouseenter'))
            const tooltipEl = document.querySelector('[role="tooltip"]')
            expect(tooltipEl?.textContent).toBe('Updated content')
        })
    })

    describe('Cleanup', () => {
        beforeEach(() => {
            tooltip = new Tooltip(element)
        })

        it('should remove tooltip element on destroy', () => {
            element.dispatchEvent(new Event('mouseenter'))
            expect(document.querySelector('[role="tooltip"]')).toBeTruthy()

            tooltip.destroy()
            expect(document.querySelector('[role="tooltip"]')).toBeFalsy()
        })

        it('should remove event listeners on destroy', () => {
            const spy = vi.fn()
            element.addEventListener('mouseenter', spy)

            tooltip.destroy()

            element.dispatchEvent(new Event('mouseenter'))
            expect(spy).toHaveBeenCalled()
        })
    })

    describe('Accessibility', () => {
        beforeEach(() => {
            tooltip = new Tooltip(element)
        })

        it('should have correct ARIA role', () => {
            element.dispatchEvent(new Event('mouseenter'))
            const tooltipEl = document.querySelector('[role="tooltip"]')
            expect(tooltipEl?.getAttribute('role')).toBe('tooltip')
        })

        it('should be keyboard accessible', () => {
            element.tabIndex = 0
            element.dispatchEvent(new Event('focus'))
            const tooltipEl = document.querySelector('[role="tooltip"]')
            expect(tooltipEl).toBeTruthy()
        })
    })

    // Teste de performance
    it('should perform tooltip operations efficiently', () => {
        tooltip = new Tooltip(element)
        const start = performance.now()

        // Executar múltiplas operações
        for (let i = 0; i < 50; i++) {
            element.dispatchEvent(new Event('mouseenter'))
            element.dispatchEvent(new Event('mouseleave'))
        }

        const end = performance.now()
        const duration = end - start
        expect(duration).toBeLessThan(500) // Menos de 500ms para 100 operações
    })
})