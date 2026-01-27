import { describe, it, expect, beforeEach, afterEach } from 'vitest';
import { FormValidator } from '../components/FormValidator';

describe('FormValidator Component', () => {
    let form: HTMLFormElement;
    let validator: FormValidator;

    beforeEach(() => {
        form = document.createElement('form');
        form.innerHTML = `
      <div>
        <input type="text" name="name" data-validate="required|min:2|max:50">
      </div>
      <div>
        <input type="email" name="email" data-validate="required|email">
      </div>
      <div>
        <input type="password" name="password" data-validate="required|min:8">
      </div>
      <div>
        <input type="password" name="password_confirmation" data-validate="required|confirmed:password">
      </div>
      <div>
        <input type="number" name="age" data-validate="numeric|minValue:18|maxValue:120">
      </div>
      <div>
        <input type="text" name="cpf" data-validate="cpf">
      </div>
      <div>
        <input type="url" name="website" data-validate="url">
      </div>
      <button type="submit">Submit</button>
    `;
        document.body.appendChild(form);

        validator = new FormValidator(form);
    });

    afterEach(() => {
        validator.destroy();
        document.body.removeChild(form);
    });

    describe('Initialization', () => {
        it('should initialize with form fields', () => {
            expect(validator).toBeDefined();
            expect(validator.isValid()).toBe(true); // No validation triggered yet
        });

        it('should parse validation rules from data attributes', () => {
            const nameField = form.querySelector('[name="name"]') as HTMLInputElement;
            expect(nameField).toBeTruthy();
            expect(nameField.dataset.validate).toBe('required|min:2|max:50');
        });
    });

    describe('Built-in Validators', () => {
        describe('Required Validation', () => {
            it('should validate required fields', () => {
                const nameField = form.querySelector('[name="name"]') as HTMLInputElement;

                // Empty field should be invalid
                expect(validator.validate()).toBe(false);
                expect(validator.errors().name).toContain('Campo obrigatório');

                // Fill field should be valid
                nameField.value = 'John';
                expect(validator.validate()).toBe(false); // Other fields still empty
                expect(validator.errors().name).toHaveLength(0);
            });
        });

        describe('Email Validation', () => {
            it('should validate email format', () => {
                const emailField = form.querySelector('[name="email"]') as HTMLInputElement;
                const nameField = form.querySelector('[name="name"]') as HTMLInputElement;
                nameField.value = 'John'; // Fill required field

                emailField.value = 'invalid-email';
                validator.validate();
                expect(validator.errors().email).toContain('Email inválido');

                emailField.value = 'john@example.com';
                validator.validate();
                expect(validator.errors().email).toHaveLength(0);
            });
        });

        describe('Length Validation', () => {
            it('should validate minimum length', () => {
                const nameField = form.querySelector('[name="name"]') as HTMLInputElement;

                nameField.value = 'A'; // Too short
                validator.validate();
                expect(validator.errors().name).toContain('Mínimo 2 caracteres');

                nameField.value = 'John'; // Valid
                validator.validate();
                expect(validator.errors().name).toHaveLength(0);
            });

            it('should validate maximum length', () => {
                const nameField = form.querySelector('[name="name"]') as HTMLInputElement;

                nameField.value = 'A'.repeat(51); // Too long
                validator.validate();
                expect(validator.errors().name).toContain('Máximo 50 caracteres');

                nameField.value = 'John'; // Valid
                validator.validate();
                expect(validator.errors().name).toHaveLength(0);
            });
        });

        describe('Numeric Validation', () => {
            it('should validate numeric fields', () => {
                const ageField = form.querySelector('[name="age"]') as HTMLInputElement;

                ageField.value = 'abc';
                validator.validate();
                expect(validator.errors().age).toContain('Apenas números');

                ageField.value = '25';
                validator.validate();
                expect(validator.errors().age).toHaveLength(0);
            });

            it('should validate minimum and maximum values', () => {
                const ageField = form.querySelector('[name="age"]') as HTMLInputElement;

                ageField.value = '15'; // Too young
                validator.validate();
                expect(validator.errors().age).toContain('Valor mínimo: 18');

                ageField.value = '150'; // Too old
                validator.validate();
                expect(validator.errors().age).toContain('Valor máximo: 120');

                ageField.value = '25'; // Valid
                validator.validate();
                expect(validator.errors().age).toHaveLength(0);
            });
        });

        describe('CPF Validation', () => {
            it('should validate CPF format', () => {
                const cpfField = form.querySelector('[name="cpf"]') as HTMLInputElement;

                cpfField.value = '12345678901'; // Invalid CPF
                validator.validate();
                expect(validator.errors().cpf).toContain('CPF inválido');

                cpfField.value = '123.456.789-09'; // Valid format (this is a test CPF)
                validator.validate();
                expect(validator.errors().cpf).toHaveLength(0);
            });
        });

        describe('URL Validation', () => {
            it('should validate URL format', () => {
                const urlField = form.querySelector('[name="website"]') as HTMLInputElement;

                urlField.value = 'not-a-url';
                validator.validate();
                expect(validator.errors().website).toContain('URL inválida');

                urlField.value = 'https://example.com';
                validator.validate();
                expect(validator.errors().website).toHaveLength(0);
            });
        });

        describe('Confirmation Validation', () => {
            it('should validate field confirmation', () => {
                const passwordField = form.querySelector('[name="password"]') as HTMLInputElement;
                const confirmField = form.querySelector('[name="password_confirmation"]') as HTMLInputElement;

                passwordField.value = 'password123';
                confirmField.value = 'different';
                validator.validate();
                expect(validator.errors().password_confirmation).toContain('Campos não conferem');

                confirmField.value = 'password123';
                validator.validate();
                expect(validator.errors().password_confirmation).toHaveLength(0);
            });
        });
    });

    describe('Form Submission', () => {
        it('should prevent submission when invalid', () => {
            const submitEvent = new Event('submit', { cancelable: true });
            form.dispatchEvent(submitEvent);

            expect(submitEvent.defaultPrevented).toBe(true);
        });

        it('should allow submission when valid', () => {
            // Fill all required fields
            const fields = {
                name: 'John Doe',
                email: 'john@example.com',
                password: 'password123',
                password_confirmation: 'password123',
                age: '25',
                cpf: '123.456.789-09',
                website: 'https://example.com'
            };

            Object.entries(fields).forEach(([name, value]) => {
                const field = form.querySelector(`[name="${name}"]`) as HTMLInputElement;
                if (field) field.value = value;
            });

            const submitEvent = new Event('submit', { cancelable: true });
            form.dispatchEvent(submitEvent);

            expect(submitEvent.defaultPrevented).toBe(false);
        });
    });

    describe('Real-time Validation', () => {
        it('should validate on blur', () => {
            const nameField = form.querySelector('[name="name"]') as HTMLInputElement;

            nameField.focus();
            nameField.blur();

            expect(validator.errors().name).toContain('Campo obrigatório');
        });

        it('should clear errors on input when field has errors', () => {
            const nameField = form.querySelector('[name="name"]') as HTMLInputElement;

            // Trigger validation error
            nameField.focus();
            nameField.blur();
            expect(validator.errors().name).toBeDefined();

            // Type something valid
            nameField.value = 'Jo';
            nameField.dispatchEvent(new Event('input'));

            expect(validator.errors().name).toHaveLength(0);
        });
    });

    describe('Error Display', () => {
        it('should show error messages in DOM', () => {
            const nameField = form.querySelector('[name="name"]') as HTMLInputElement;

            nameField.focus();
            nameField.blur();

            const errorEl = nameField.parentElement?.querySelector('.field-error');
            expect(errorEl).toBeTruthy();
            expect(errorEl?.textContent).toBe('Campo obrigatório');
            expect(errorEl?.classList.contains('text-red-500')).toBe(true);
        });

        it('should update field styling for errors', () => {
            const nameField = form.querySelector('[name="name"]') as HTMLInputElement;

            nameField.focus();
            nameField.blur();

            expect(nameField.classList.contains('border-red-500')).toBe(true);
            expect(nameField.getAttribute('aria-invalid')).toBe('true');
        });

        it('should clear errors when reset', () => {
            const nameField = form.querySelector('[name="name"]') as HTMLInputElement;

            // Create error
            nameField.focus();
            nameField.blur();

            // Reset
            validator.reset();

            expect(nameField.classList.contains('border-red-500')).toBe(false);
            expect(nameField.parentElement?.querySelector('.field-error')).toBeFalsy();
        });
    });

    describe('API Methods', () => {
        it('should return errors object', () => {
            const nameField = form.querySelector('[name="name"]') as HTMLInputElement;
            nameField.focus();
            nameField.blur();

            const errors = validator.errors();
            expect(errors.name).toContain('Campo obrigatório');
            expect(typeof errors).toBe('object');
        });

        it('should check if form is valid', () => {
            expect(validator.isValid()).toBe(true); // Initially no errors

            // Fill required fields
            const nameField = form.querySelector('[name="name"]') as HTMLInputElement;
            const emailField = form.querySelector('[name="email"]') as HTMLInputElement;
            const passwordField = form.querySelector('[name="password"]') as HTMLInputElement;
            const confirmField = form.querySelector('[name="password_confirmation"]') as HTMLInputElement;

            nameField.value = 'John';
            emailField.value = 'john@example.com';
            passwordField.value = 'password123';
            confirmField.value = 'password123';

            expect(validator.isValid()).toBe(true);
        });

        it('should set rules dynamically', () => {
            validator.setRules('name', 'required|min:5');

            const nameField = form.querySelector('[name="name"]') as HTMLInputElement;
            nameField.value = 'Hi'; // Too short
            validator.validate();

            expect(validator.errors().name).toContain('Mínimo 5 caracteres');
        });
    });

    describe('Performance', () => {
        it('should validate form efficiently', () => {
            const start = performance.now();

            for (let i = 0; i < 50; i++) {
                validator.validate();
            }

            const end = performance.now();
            const duration = end - start;

            expect(duration).toBeLessThan(500); // Should complete 50 validations in <500ms
        });
    });
});