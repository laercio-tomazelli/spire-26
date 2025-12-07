import { PerfManager } from '../types';

export class Perf implements PerfManager {
  #marks: Map<string, number> = new Map();
  #measures: Map<string, number> = new Map();

  mark(name: string): void {
    this.#marks.set(name, performance.now());
    
    // Also use native Performance API if available
    if (typeof performance !== 'undefined' && performance.mark) {
      try {
        performance.mark(name);
      } catch {
        // Ignore if mark already exists
      }
    }
  }

  measure(name: string, startMark: string, endMark?: string): number {
    const start = this.#marks.get(startMark);
    const end = endMark ? this.#marks.get(endMark) : performance.now();
    
    if (start === undefined) {
      console.warn(`Performance mark "${startMark}" not found`);
      return 0;
    }

    const duration = (end || performance.now()) - start;
    this.#measures.set(name, duration);

    // Log in development
    if (typeof process !== 'undefined' && process.env?.NODE_ENV === 'development') {
      console.log(`⏱️ ${name}: ${duration.toFixed(2)}ms`);
    }

    return duration;
  }

  getMarks(): Record<string, number> {
    return Object.fromEntries(this.#marks);
  }

  getMeasures(): Record<string, number> {
    return Object.fromEntries(this.#measures);
  }

  clear(): void {
    this.#marks.clear();
    this.#measures.clear();
    
    if (typeof performance !== 'undefined' && performance.clearMarks) {
      performance.clearMarks();
      performance.clearMeasures();
    }
  }
}

export const perf = new Perf();
