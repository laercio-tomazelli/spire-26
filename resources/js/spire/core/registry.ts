import type { SpireUIInstance, ErrorHandler } from '../types';

// WeakMap para armazenar instâncias sem poluir o DOM
export const instances = new WeakMap<HTMLElement, SpireUIInstance>();

// Helper para emitir eventos customizados
export const emit = (el: HTMLElement, eventName: string, detail: Record<string, unknown> = {}): void => {
  el.dispatchEvent(new CustomEvent(eventName, { 
    bubbles: true, 
    cancelable: true,
    detail 
  }));
};

// Global error handler reference
export let globalErrorHandler: ErrorHandler | null = null;

export function setGlobalErrorHandler(handler: ErrorHandler): void {
  globalErrorHandler = handler;
}

export function getGlobalErrorHandler(): ErrorHandler | null {
  return globalErrorHandler;
}
