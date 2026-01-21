import { EventBusManager } from '../types';
import { globalErrorHandler } from '../core/registry';

export class EventBus implements EventBusManager {
  #listeners: Map<string, Set<(...args: unknown[]) => void>> = new Map();

  on(event: string, handler: (...args: unknown[]) => void): () => void {
    if (!this.#listeners.has(event)) {
      this.#listeners.set(event, new Set());
    }
    this.#listeners.get(event)!.add(handler);
    
    // Return unsubscribe function
    return () => this.off(event, handler);
  }

  off(event: string, handler?: (...args: unknown[]) => void): void {
    if (!handler) {
      this.#listeners.delete(event);
    } else {
      this.#listeners.get(event)?.delete(handler);
    }
  }

  emit(event: string, ...args: unknown[]): void {
    this.#listeners.get(event)?.forEach(handler => {
      try {
        handler(...args);
      } catch (error) {
        console.error(`Event handler error for "${event}":`, error);
        globalErrorHandler?.(error as Error, { component: 'EventBus' });
      }
    });
  }

  once(event: string, handler: (...args: unknown[]) => void): () => void {
    const onceHandler = (...args: unknown[]) => {
      handler(...args);
      this.off(event, onceHandler);
    };
    return this.on(event, onceHandler);
  }
}

export const events = new EventBus();
