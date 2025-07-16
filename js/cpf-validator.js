/**
 * Validador de CPF para o Sistema ShieldTech
 * Integrado com a estrutura existente do projeto
 */

class CPFValidator {
    /**
     * Valida se o CPF é válido
     * @param {string} cpf - CPF a ser validado
     * @returns {boolean} - true se válido, false se inválido
     */
    static isValid(cpf) {
        // Remove caracteres não numéricos
        cpf = cpf.replace(/\D/g, '');
        
        // Verifica se tem 11 dígitos
        if (cpf.length !== 11) return false;
        
        // Verifica se todos os dígitos são iguais
        if (/^(\d)\1{10}$/.test(cpf)) return false;
        
        // Validação do primeiro dígito verificador
        let sum = 0;
        for (let i = 0; i < 9; i++) {
            sum += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let remainder = (sum * 10) % 11;
        if (remainder === 10 || remainder === 11) remainder = 0;
        if (remainder !== parseInt(cpf.charAt(9))) return false;
        
        // Validação do segundo dígito verificador
        sum = 0;
        for (let i = 0; i < 10; i++) {
            sum += parseInt(cpf.charAt(i)) * (11 - i);
        }
        remainder = (sum * 10) % 11;
        if (remainder === 10 || remainder === 11) remainder = 0;
        if (remainder !== parseInt(cpf.charAt(10))) return false;
        
        return true;
    }

    /**
     * Formata o CPF com máscara
     * @param {string} cpf - CPF a ser formatado
     * @returns {string} - CPF formatado
     */
    static format(cpf) {
        cpf = cpf.replace(/\D/g, '');
        if (cpf.length <= 11) {
            cpf = cpf.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
        }
        return cpf;
    }

    /**
     * Remove formatação do CPF
     * @param {string} cpf - CPF formatado
     * @returns {string} - CPF sem formatação
     */
    static clean(cpf) {
        return cpf.replace(/\D/g, '');
    }

    /**
     * Configura validação em tempo real para um campo de CPF
     * @param {string} inputId - ID do campo de input
     * @param {string} errorElementId - ID do elemento para mostrar erros
     * @param {string} iconElementId - ID do elemento para mostrar ícone (opcional)
     */
    static setupValidation(inputId, errorElementId, iconElementId = null) {
        const cpfInput = document.getElementById(inputId);
        const errorElement = document.getElementById(errorElementId);
        const iconElement = iconElementId ? document.getElementById(iconElementId) : null;

        if (!cpfInput) return;

        let validationTimeout;

        // Aplicar máscara durante a digitação
        cpfInput.addEventListener('input', (e) => {
            const cursorPosition = e.target.selectionStart;
            const oldValue = e.target.value;
            const newValue = this.format(e.target.value);
            
            e.target.value = newValue;
            
            // Ajustar posição do cursor após formatação
            const diff = newValue.length - oldValue.length;
            e.target.setSelectionRange(cursorPosition + diff, cursorPosition + diff);

            // Limpar classes de validação
            cpfInput.classList.remove('valid', 'invalid');
            if (errorElement) {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
            if (iconElement) {
                iconElement.innerHTML = '';
            }

            // Validar após pausa na digitação
            clearTimeout(validationTimeout);
            validationTimeout = setTimeout(() => {
                this.validateField(cpfInput, errorElement, iconElement);
            }, 500);
        });

        // Validação no blur (quando sai do campo)
        cpfInput.addEventListener('blur', () => {
            this.validateField(cpfInput, errorElement, iconElement);
        });

        // Validação no envio do formulário
        const form = cpfInput.closest('form');
        if (form) {
            form.addEventListener('submit', (e) => {
                if (!this.validateField(cpfInput, errorElement, iconElement)) {
                    e.preventDefault();
                    cpfInput.focus();
                    return false;
                }
            });
        }
    }

    /**
     * Valida um campo específico e atualiza a UI
     * @param {HTMLElement} input - Campo de input
     * @param {HTMLElement} errorElement - Elemento de erro
     * @param {HTMLElement} iconElement - Elemento de ícone
     * @returns {boolean} - true se válido
     */
    static validateField(input, errorElement, iconElement) {
        const cpf = input.value.trim();
        
        if (!cpf) {
            // Campo vazio - remover validação visual
            input.classList.remove('valid', 'invalid');
            if (errorElement) {
                errorElement.style.display = 'none';
            }
            if (iconElement) {
                iconElement.innerHTML = '';
            }
            return true; // Permitir campo vazio se não for obrigatório
        }

        const isValid = this.isValid(cpf);
        
        if (isValid) {
            input.classList.add('valid');
            input.classList.remove('invalid');
            if (errorElement) {
                errorElement.textContent = '✓ CPF válido';
                errorElement.style.color = '#2ecc71';
                errorElement.style.display = 'block';
            }
            if (iconElement) {
                iconElement.innerHTML = '<i class="fas fa-check-circle valid"></i>';
            }
        } else {
            input.classList.add('invalid');
            input.classList.remove('valid');
            if (errorElement) {
                errorElement.textContent = 'CPF inválido';
                errorElement.style.color = '#e74c3c';
                errorElement.style.display = 'block';
            }
            if (iconElement) {
                iconElement.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            }
        }
        
        return isValid;
    }

    /**
     * Verifica se CPF já existe no banco (para uso com AJAX)
     * @param {string} cpf - CPF a ser verificado
     * @param {number} excludeId - ID a ser excluído da verificação (para edição)
     * @param {string} table - Tabela a ser verificada (moradores, funcionarios, visitantes)
     * @returns {Promise<boolean>} - true se existe
     */
    static async checkExists(cpf, excludeId = null, table = 'moradores') {
        try {
            const formData = new FormData();
            formData.append('action', 'check_cpf');
            formData.append('cpf', this.clean(cpf));
            formData.append('table', table);
            if (excludeId) {
                formData.append('exclude_id', excludeId);
            }

            const response = await fetch('../../api/check_cpf.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            return result.exists;
        } catch (error) {
            console.error('Erro ao verificar CPF:', error);
            return false;
        }
    }

    /**
     * Configura validação completa com verificação de duplicidade
     * @param {string} inputId - ID do campo
     * @param {string} errorElementId - ID do elemento de erro
     * @param {string} table - Tabela para verificar duplicidade
     * @param {number} excludeId - ID para excluir da verificação
     */
    static setupCompleteValidation(inputId, errorElementId, table = 'moradores', excludeId = null) {
        const cpfInput = document.getElementById(inputId);
        const errorElement = document.getElementById(errorElementId);

        if (!cpfInput) return;

        // Configurar validação básica
        this.setupValidation(inputId, errorElementId);

        // Adicionar verificação de duplicidade
        let duplicateTimeout;
        cpfInput.addEventListener('input', () => {
            clearTimeout(duplicateTimeout);
            duplicateTimeout = setTimeout(async () => {
                const cpf = cpfInput.value.trim();
                
                if (cpf && this.isValid(cpf)) {
                    const exists = await this.checkExists(cpf, excludeId, table);
                    
                    if (exists) {
                        cpfInput.classList.add('invalid');
                        cpfInput.classList.remove('valid');
                        if (errorElement) {
                            errorElement.textContent = 'Este CPF já está cadastrado!';
                            errorElement.style.color = '#e74c3c';
                            errorElement.style.display = 'block';
                        }
                    }
                }
            }, 800);
        });
    }
}

// Função para inicializar validação em todas as páginas
document.addEventListener('DOMContentLoaded', () => {
    // Detectar qual página estamos e configurar validação apropriada
    const currentPage = window.location.pathname;
    
    if (currentPage.includes('moradores')) {
        CPFValidator.setupCompleteValidation('cpf', 'cpf-error', 'moradores');
    } else if (currentPage.includes('funcionarios')) {
        CPFValidator.setupCompleteValidation('cpf', 'cpf-error', 'funcionarios');
    } else if (currentPage.includes('visitantes')) {
        // Para visitantes, usar campo num_documento
        const docInput = document.getElementById('num_documento');
        if (docInput) {
            CPFValidator.setupCompleteValidation('num_documento', 'cpf-error', 'visitantes');
        }
    }
});

// Exportar para uso global
window.CPFValidator = CPFValidator;