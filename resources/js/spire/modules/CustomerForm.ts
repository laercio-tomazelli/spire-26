/**
 * Customer Form Module
 * Handles CEP lookup and form field toggling for customer forms
 */

import { mask } from '../utilities/Mask';
import { toast } from '../utilities/Toast';

interface PostalCodeResponse {
    success: boolean;
    data?: {
        code: string;
        formatted_code: string;
        street: string | null;
        neighborhood: string | null;
        city: string | null;
        state: string | null;
        complement: string | null;
    };
    message?: string;
}

export class CustomerForm {
    private form: HTMLFormElement | null;
    private cepInput: HTMLInputElement | null;
    private cepLoading: HTMLElement | null;
    private cepError: HTMLElement | null;
    private customerTypeInputs: NodeListOf<HTMLInputElement>;
    private debounceTimer: ReturnType<typeof setTimeout> | null = null;

    constructor() {
        this.form = document.getElementById('customer-form') as HTMLFormElement | null;
        this.cepInput = document.getElementById('postal_code') as HTMLInputElement | null;
        this.cepLoading = document.getElementById('cep-loading');
        this.cepError = document.getElementById('cep-error');
        this.customerTypeInputs = document.querySelectorAll('input[name="customer_type"]');

        this.init();
    }

    private init(): void {
        if (!this.form) return;

        this.initMasks();
        this.initCepSearch();
        this.initCustomerTypeToggle();
    }

    /**
     * Initialize input masks
     */
    private initMasks(): void {
        // CEP mask
        if (this.cepInput) {
            mask.apply(this.cepInput, 'cep');
        }

        // Document mask (CPF/CNPJ) - dynamic based on customer type
        const documentInput = this.form?.querySelector('input[name="document"]') as HTMLInputElement | null;
        if (documentInput) {
            this.applyDocumentMask(documentInput);
        }

        // Phone masks
        const phoneInputs = this.form?.querySelectorAll('input[data-mask="phone"]') as NodeListOf<HTMLInputElement>;
        phoneInputs?.forEach(input => {
            mask.apply(input, 'landline');
        });

        // Mobile masks
        const mobileInputs = this.form?.querySelectorAll('input[data-mask="mobile"]') as NodeListOf<HTMLInputElement>;
        mobileInputs?.forEach(input => {
            mask.apply(input, 'phone');
        });
    }

    /**
     * Apply document mask based on customer type (CPF or CNPJ)
     */
    private applyDocumentMask(input: HTMLInputElement): void {
        const customerType = this.getCustomerType();
        const maskType = customerType === 'PJ' ? 'cnpj' : 'cpf';
        mask.apply(input, maskType);
    }

    /**
     * Get current customer type
     */
    private getCustomerType(): string {
        const checked = document.querySelector('input[name="customer_type"]:checked') as HTMLInputElement | null;
        return checked?.value || 'PF';
    }

    /**
     * Initialize CEP search functionality
     */
    private initCepSearch(): void {
        if (!this.cepInput) return;

        this.cepInput.addEventListener('input', () => {
            const value = this.cepInput?.value.replace(/\D/g, '') || '';

            // Clear previous timer
            if (this.debounceTimer) {
                clearTimeout(this.debounceTimer);
            }

            // Clear error
            this.hideCepError();

            // Only search when we have 8 digits
            if (value.length === 8) {
                this.debounceTimer = setTimeout(() => {
                    this.searchCep(value);
                }, 300);
            }
        });

        // Also search on blur if value is complete
        this.cepInput.addEventListener('blur', () => {
            const value = this.cepInput?.value.replace(/\D/g, '') || '';
            if (value.length === 8) {
                this.searchCep(value);
            }
        });
    }

    /**
     * Search CEP and fill address fields
     */
    private async searchCep(cep: string): Promise<void> {
        this.showCepLoading();
        this.hideCepError();

        try {
            const response = await fetch(`/api/postal-codes/${cep}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            const data: PostalCodeResponse = await response.json();

            if (data.success && data.data) {
                this.fillAddressFields(data.data);
            } else {
                this.showCepError(data.message || 'CEP não encontrado');
            }
        } catch (error) {
            console.error('Error fetching CEP:', error);
            this.showCepError('Erro ao buscar CEP. Tente novamente.');
        } finally {
            this.hideCepLoading();
        }
    }

    /**
     * Fill address fields with postal code data
     */
    private fillAddressFields(data: PostalCodeResponse['data']): void {
        if (!data) return;

        const fields: Record<string, string | null> = {
            'address': data.street,
            'neighborhood': data.neighborhood,
            'city': data.city,
            'state': data.state,
        };

        for (const [fieldName, value] of Object.entries(fields)) {
            const input = document.getElementById(fieldName) as HTMLInputElement | HTMLSelectElement | null;
            if (input && value) {
                input.value = value;
                // Trigger change event for select elements
                if (input.tagName === 'SELECT') {
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        }

        // Focus on address number field after filling
        const numberInput = document.getElementById('address_number') as HTMLInputElement | null;
        if (numberInput) {
            numberInput.focus();
        }

        // Show success notification
        toast.success('Endereço preenchido automaticamente!');
    }

    /**
     * Initialize customer type toggle (PF/PJ)
     */
    private initCustomerTypeToggle(): void {
        this.customerTypeInputs.forEach(input => {
            input.addEventListener('change', () => {
                this.updateFieldsVisibility();
                this.updateDocumentMask();
            });
        });

        // Initial state
        this.updateFieldsVisibility();
    }

    /**
     * Update fields visibility based on customer type
     */
    private updateFieldsVisibility(): void {
        const customerType = this.getCustomerType();
        const isPJ = customerType === 'PJ';

        // Trade name - only for PJ
        const tradeNameField = document.getElementById('trade-name-field');
        if (tradeNameField) {
            tradeNameField.classList.toggle('hidden', !isPJ);
        }

        // State registration - only for PJ
        const stateRegistrationField = document.getElementById('state-registration-field');
        if (stateRegistrationField) {
            stateRegistrationField.classList.toggle('hidden', !isPJ);
        }

        // Birth date - only for PF
        const birthDateField = document.getElementById('birth-date-field');
        if (birthDateField) {
            birthDateField.classList.toggle('hidden', isPJ);
        }

        // Update document label
        const documentInput = this.form?.querySelector('input[name="document"]') as HTMLInputElement | null;
        if (documentInput) {
            const label = documentInput.closest('div')?.querySelector('label');
            if (label) {
                label.textContent = isPJ ? 'CNPJ' : 'CPF';
            }
            documentInput.placeholder = isPJ ? '00.000.000/0000-00' : '000.000.000-00';
        }
    }

    /**
     * Update document mask based on customer type
     */
    private updateDocumentMask(): void {
        const documentInput = this.form?.querySelector('input[name="document"]') as HTMLInputElement | null;
        if (documentInput) {
            // Clear current value to reset mask
            const rawValue = documentInput.value.replace(/\D/g, '');
            documentInput.value = '';
            this.applyDocumentMask(documentInput);
            // Re-apply the value with new mask
            if (rawValue) {
                documentInput.value = rawValue;
                documentInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
    }

    /**
     * Show CEP loading indicator
     */
    private showCepLoading(): void {
        this.cepLoading?.classList.remove('hidden');
    }

    /**
     * Hide CEP loading indicator
     */
    private hideCepLoading(): void {
        this.cepLoading?.classList.add('hidden');
    }

    /**
     * Show CEP error message
     */
    private showCepError(message: string): void {
        if (this.cepError) {
            this.cepError.textContent = message;
            this.cepError.classList.remove('hidden');
        }
    }

    /**
     * Hide CEP error message
     */
    private hideCepError(): void {
        if (this.cepError) {
            this.cepError.textContent = '';
            this.cepError.classList.add('hidden');
        }
    }
}

/**
 * Initialize customer form
 */
export function initCustomerForm(): void {
    new CustomerForm();
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('customer-form')) {
            initCustomerForm();
        }
    });
} else {
    if (document.getElementById('customer-form')) {
        initCustomerForm();
    }
}
