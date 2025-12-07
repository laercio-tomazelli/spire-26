import type { FormValidatorInstance } from '../types';
import { instances, emit } from '../core';

export class FormValidator implements FormValidatorInstance {
  #el: HTMLFormElement;
  #fields: Map<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement, string> = new Map();
  #errors: Record<string, string[]> = {};
  #validators: Record<string, (value: string, param?: string) => boolean | string>;

  constructor(el: HTMLFormElement) {
    this.#el = el;
    this.#validators = {
      required: (v) => v.trim().length > 0 || 'Campo obrigatório',
      email: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) || 'Email inválido',
      min: (v, p) => v.length >= parseInt(p || '0') || `Mínimo ${p} caracteres`,
      max: (v, p) => v.length <= parseInt(p || '999') || `Máximo ${p} caracteres`,
      minValue: (v, p) => parseFloat(v) >= parseFloat(p || '0') || `Valor mínimo: ${p}`,
      maxValue: (v, p) => parseFloat(v) <= parseFloat(p || '999999') || `Valor máximo: ${p}`,
      pattern: (v, p) => new RegExp(p || '').test(v) || 'Formato inválido',
      url: (v) => /^https?:\/\/.+/.test(v) || 'URL inválida',
      numeric: (v) => /^\d+$/.test(v) || 'Apenas números',
      alpha: (v) => /^[a-zA-Z]+$/.test(v) || 'Apenas letras',
      alphanumeric: (v) => /^[a-zA-Z0-9]+$/.test(v) || 'Apenas letras e números',
      phone: (v) => /^[\d\s\-\+\(\)]+$/.test(v) || 'Telefone inválido',
      cpf: (v) => this.#validateCPF(v) || 'CPF inválido',
      cnpj: (v) => this.#validateCNPJ(v) || 'CNPJ inválido',
      date: (v) => !isNaN(Date.parse(v)) || 'Data inválida',
      confirmed: (v, p) => {
        const confirmField = this.#el.querySelector(`[name="${p}"]`) as HTMLInputElement;
        return v === confirmField?.value || 'Campos não conferem';
      }
    };

    this.#parseFields();
    this.#setupListeners();
  }

  #validateCPF(cpf: string): boolean {
    cpf = cpf.replace(/\D/g, '');
    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
    let sum = 0, remainder;
    for (let i = 1; i <= 9; i++) sum += parseInt(cpf[i - 1]) * (11 - i);
    remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) remainder = 0;
    if (remainder !== parseInt(cpf[9])) return false;
    sum = 0;
    for (let i = 1; i <= 10; i++) sum += parseInt(cpf[i - 1]) * (12 - i);
    remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) remainder = 0;
    return remainder === parseInt(cpf[10]);
  }

  #validateCNPJ(cnpj: string): boolean {
    cnpj = cnpj.replace(/\D/g, '');
    if (cnpj.length !== 14) return false;
    const weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    const weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    let sum = 0;
    for (let i = 0; i < 12; i++) sum += parseInt(cnpj[i]) * weights1[i];
    let remainder = sum % 11;
    if (parseInt(cnpj[12]) !== (remainder < 2 ? 0 : 11 - remainder)) return false;
    sum = 0;
    for (let i = 0; i < 13; i++) sum += parseInt(cnpj[i]) * weights2[i];
    remainder = sum % 11;
    return parseInt(cnpj[13]) === (remainder < 2 ? 0 : 11 - remainder);
  }

  #parseFields(): void {
    this.#el.querySelectorAll<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>('[data-validate]').forEach(field => {
      const rules = field.dataset.validate || '';
      this.#fields.set(field, rules);
    });
  }

  #setupListeners(): void {
    this.#el.addEventListener('submit', (e) => {
      if (!this.validate()) {
        e.preventDefault();
        emit(this.#el, 'form:invalid', { errors: this.#errors });
      } else {
        emit(this.#el, 'form:valid', {});
      }
    });

    // Real-time validation on blur
    this.#fields.forEach((_, field) => {
      field.addEventListener('blur', () => this.#validateField(field));
      field.addEventListener('input', () => {
        if (this.#errors[field.name]?.length) {
          this.#validateField(field);
        }
      });
    });
  }

  #validateField(field: HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement): boolean {
    const rules = this.#fields.get(field) || '';
    const value = field.value;
    const fieldErrors: string[] = [];

    rules.split('|').forEach(rule => {
      const [ruleName, param] = rule.split(':');
      const validator = this.#validators[ruleName];
      
      if (validator) {
        const result = validator(value, param);
        if (result !== true && typeof result === 'string') {
          fieldErrors.push(result);
        }
      }
    });

    this.#errors[field.name] = fieldErrors;
    this.#showFieldError(field, fieldErrors);
    
    return fieldErrors.length === 0;
  }

  #showFieldError(field: HTMLElement, errors: string[]): void {
    const hasError = errors.length > 0;
    field.classList.toggle('border-red-500', hasError);
    field.classList.toggle('border-gray-300', !hasError);
    field.setAttribute('aria-invalid', String(hasError));

    // Find or create error container
    let errorEl = field.parentElement?.querySelector('.field-error');
    if (!errorEl && hasError) {
      errorEl = document.createElement('p');
      errorEl.className = 'field-error text-red-500 text-sm mt-1';
      field.parentElement?.appendChild(errorEl);
    }
    
    if (errorEl) {
      errorEl.textContent = errors[0] || '';
      (errorEl as HTMLElement).style.display = hasError ? 'block' : 'none';
    }
  }

  validate(): boolean {
    this.#errors = {};
    let isValid = true;

    this.#fields.forEach((_, field) => {
      if (!this.#validateField(field)) {
        isValid = false;
      }
    });

    if (!isValid) {
      // Focus first invalid field
      const firstInvalid = this.#el.querySelector('[aria-invalid="true"]') as HTMLElement;
      firstInvalid?.focus();
    }

    return isValid;
  }

  isValid(): boolean {
    return Object.values(this.#errors).every(e => e.length === 0);
  }

  errors(): Record<string, string[]> {
    return { ...this.#errors };
  }

  reset(): this {
    this.#errors = {};
    this.#fields.forEach((_, field) => {
      field.classList.remove('border-red-500');
      field.classList.add('border-gray-300');
      field.removeAttribute('aria-invalid');
      field.parentElement?.querySelector('.field-error')?.remove();
    });
    return this;
  }

  setRules(fieldName: string, rules: string): this {
    const field = this.#el.querySelector(`[name="${fieldName}"]`) as HTMLInputElement;
    if (field) {
      this.#fields.set(field, rules);
      field.dataset.validate = rules;
    }
    return this;
  }

  destroy(): void {
    instances.delete(this.#el);
  }
}
