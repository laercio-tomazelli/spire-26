import { CurrencyManager, CurrencyOptions } from '../types';

export class Currency implements CurrencyManager {
  format(value: number, options?: CurrencyOptions): string {
    const opts = {
      locale: options?.locale || 'pt-BR',
      currency: options?.currency || 'BRL',
      ...options
    };

    if (opts.symbol !== undefined) {
      // Custom formatting
      const absValue = Math.abs(value);
      const decimal = opts.decimal || ',';
      const thousands = opts.thousands || '.';
      
      const [intPart, decPart] = absValue.toFixed(2).split('.');
      const formattedInt = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousands);
      const formatted = `${opts.symbol} ${formattedInt}${decimal}${decPart}`;
      
      return value < 0 ? `-${formatted}` : formatted;
    }

    return new Intl.NumberFormat(opts.locale, {
      style: 'currency',
      currency: opts.currency
    }).format(value);
  }

  parse(value: string): number {
    // Remove currency symbol and spaces
    let cleaned = value.replace(/[R$€$£¥\s]/g, '').trim();
    
    // Detect format (1.234,56 or 1,234.56)
    const lastComma = cleaned.lastIndexOf(',');
    const lastDot = cleaned.lastIndexOf('.');
    
    if (lastComma > lastDot) {
      // Brazilian format: 1.234,56
      cleaned = cleaned.replace(/\./g, '').replace(',', '.');
    } else {
      // US format: 1,234.56
      cleaned = cleaned.replace(/,/g, '');
    }
    
    const result = parseFloat(cleaned);
    return isNaN(result) ? 0 : result;
  }
}

export const currency = new Currency();
