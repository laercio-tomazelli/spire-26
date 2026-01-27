// Setup para testes do Spire UI
import { beforeAll, afterEach, vi } from 'vitest'

// Mock para globalThis ou window se necessário
beforeAll(() => {
    // Configurações globais para testes
    // Mock scrollTo para jsdom
    Object.defineProperty(window, 'scrollTo', {
        writable: true,
        value: vi.fn(),
    })
})

// Limpar DOM após cada teste
afterEach(() => {
    document.body.innerHTML = ''
})