import { MaskManager, MaskDefinition } from '../types';
import { currency } from './Currency';

export class Mask implements MaskManager {
  #masks: WeakMap<HTMLInputElement, { cleanup: () => void; rawValue: string }> = new WeakMap();

  #patterns: Record<string, MaskDefinition> = {
    cpf: { pattern: '###.###.###-##', placeholder: '000.000.000-00' },
    cnpj: { pattern: '##.###.###/####-##', placeholder: '00.000.000/0000-00' },
    phone: { pattern: '(##) #####-####', placeholder: '(00) 00000-0000' },
    landline: { pattern: '(##) ####-####', placeholder: '(00) 0000-0000' },
    cep: { pattern: '#####-###', placeholder: '00000-000' },
    date: { pattern: '##/##/####', placeholder: 'DD/MM/AAAA' },
    time: { pattern: '##:##', placeholder: 'HH:MM' },
    creditcard: { pattern: '#### #### #### ####', placeholder: '0000 0000 0000 0000' },
    money: { pattern: 'R$ #.###.###,##', placeholder: 'R$ 0,00' }
  };

  apply(input: HTMLInputElement, mask: string | MaskDefinition): void {
    // Remove existing mask
    this.remove(input);

    const definition = typeof mask === 'string' 
      ? (this.#patterns[mask] || { pattern: mask }) 
      : mask;

    const formatValue = (value: string): string => {
      // Special handling for money
      if (mask === 'money') {
        return this.#formatMoney(value);
      }

      const pattern = definition.pattern;
      const digits = value.replace(/\D/g, '');
      let result = '';
      let digitIndex = 0;

      for (let i = 0; i < pattern.length && digitIndex < digits.length; i++) {
        if (pattern[i] === '#') {
          result += digits[digitIndex];
          digitIndex++;
        } else {
          result += pattern[i];
          if (pattern[i] === digits[digitIndex]) {
            digitIndex++;
          }
        }
      }

      return result;
    };

    const handleInput = (e: Event) => {
      const target = e.target as HTMLInputElement;
      const cursorPos = target.selectionStart || 0;
      const oldLength = target.value.length;
      
      target.value = formatValue(target.value);
      
      // Adjust cursor position
      const newLength = target.value.length;
      const diff = newLength - oldLength;
      const newPos = Math.max(0, cursorPos + diff);
      
      requestAnimationFrame(() => {
        target.setSelectionRange(newPos, newPos);
      });
    };

    const handleKeydown = (e: KeyboardEvent) => {
      // Allow navigation keys
      if (['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Enter'].includes(e.key)) {
        return;
      }
      
      // Allow Ctrl/Cmd combinations
      if (e.ctrlKey || e.metaKey) {
        return;
      }
      
      // Only allow digits
      if (!/^\d$/.test(e.key)) {
        e.preventDefault();
      }
    };

    input.addEventListener('input', handleInput);
    input.addEventListener('keydown', handleKeydown);

    // Set placeholder
    if (definition.placeholder) {
      input.placeholder = definition.placeholder;
    }

    // Format initial value
    if (input.value) {
      input.value = formatValue(input.value);
    }

    this.#masks.set(input, {
      cleanup: () => {
        input.removeEventListener('input', handleInput);
        input.removeEventListener('keydown', handleKeydown);
      },
      rawValue: ''
    });
  }

  #formatMoney(value: string): string {
    const digits = value.replace(/\D/g, '');
    if (!digits) return '';
    
    const number = parseInt(digits, 10) / 100;
    return currency.format(number);
  }

  remove(input: HTMLInputElement): void {
    const data = this.#masks.get(input);
    if (data) {
      data.cleanup();
      this.#masks.delete(input);
    }
  }

  getValue(input: HTMLInputElement): string {
    return input.value.replace(/\D/g, '');
  }
}

export const mask = new Mask();
