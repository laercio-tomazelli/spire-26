import { HttpClient, HttpOptions, HttpResponse } from '../types';

export class Http implements HttpClient {
  #defaultHeaders: Record<string, string> = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  };

  async #request<T>(method: string, url: string, data?: unknown, options?: HttpOptions): Promise<HttpResponse<T>> {
    const controller = new AbortController();
    const timeout = options?.timeout || 30000;
    const timeoutId = setTimeout(() => controller.abort(), timeout);

    try {
      // Get CSRF token from meta tag (Laravel)
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      
      const headers: Record<string, string> = {
        ...this.#defaultHeaders,
        ...options?.headers
      };
      
      if (csrfToken) {
        headers['X-CSRF-TOKEN'] = csrfToken;
      }

      const response = await fetch(url, {
        method,
        headers,
        body: data ? JSON.stringify(data) : undefined,
        signal: controller.signal,
        credentials: 'same-origin'
      });

      clearTimeout(timeoutId);

      const responseData = await response.json().catch(() => null);

      return {
        data: responseData as T,
        status: response.status,
        ok: response.ok
      };
    } catch (error) {
      clearTimeout(timeoutId);
      
      if ((error as Error).name === 'AbortError') {
        throw new Error('Request timeout');
      }
      throw error;
    }
  }

  get<T = unknown>(url: string, options?: HttpOptions): Promise<HttpResponse<T>> {
    return this.#request<T>('GET', url, undefined, options);
  }

  post<T = unknown>(url: string, data?: unknown, options?: HttpOptions): Promise<HttpResponse<T>> {
    return this.#request<T>('POST', url, data, options);
  }

  put<T = unknown>(url: string, data?: unknown, options?: HttpOptions): Promise<HttpResponse<T>> {
    return this.#request<T>('PUT', url, data, options);
  }

  patch<T = unknown>(url: string, data?: unknown, options?: HttpOptions): Promise<HttpResponse<T>> {
    return this.#request<T>('PATCH', url, data, options);
  }

  delete<T = unknown>(url: string, options?: HttpOptions): Promise<HttpResponse<T>> {
    return this.#request<T>('DELETE', url, undefined, options);
  }
}

export const http = new Http();
