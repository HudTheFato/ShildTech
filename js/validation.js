// Sistema de Validação de Email
class EmailValidator {
    static async validateEmail(email) {
        // Validação básica de formato
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            return {
                valid: false,
                message: 'Formato de email inválido'
            };
        }

        // Validação de domínio comum
        const commonDomains = [
            'gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com',
            'uol.com.br', 'terra.com.br', 'bol.com.br', 'ig.com.br',
            'globo.com', 'r7.com', 'live.com', 'icloud.com'
        ];

        const domain = email.split('@')[1];
        const isCommonDomain = commonDomains.includes(domain.toLowerCase());

        if (!isCommonDomain) {
            // Para domínios não comuns, fazer uma verificação adicional
            const isValidDomain = await this.checkDomainExists(domain);
            if (!isValidDomain) {
                return {
                    valid: false,
                    message: 'Domínio do email não existe ou é inválido'
                };
            }
        }

        return {
            valid: true,
            message: 'Email válido'
        };
    }

    static async checkDomainExists(domain) {
        try {
            // Simulação de verificação de domínio
            // Em produção, você pode usar uma API real de verificação
            const response = await fetch(`https://dns.google/resolve?name=${domain}&type=MX`);
            const data = await response.json();
            return data.Status === 0 && data.Answer && data.Answer.length > 0;
        } catch (error) {
            // Se não conseguir verificar, assume que é válido
            return true;
        }
    }

    static setupEmailValidation(inputId, errorElementId) {
        const emailInput = document.getElementById(inputId);
        const errorElement = document.getElementById(errorElementId);

        if (!emailInput) return;

        let validationTimeout;

        emailInput.addEventListener('input', () => {
            clearTimeout(validationTimeout);
            
            // Remover classes de erro/sucesso
            emailInput.classList.remove('valid', 'invalid');
            if (errorElement) {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }

            // Validar após 500ms de pausa na digitação
            validationTimeout = setTimeout(async () => {
                const email = emailInput.value.trim();
                
                if (email) {
                    const validation = await this.validateEmail(email);
                    
                    if (validation.valid) {
                        emailInput.classList.add('valid');
                        if (errorElement) {
                            errorElement.textContent = '✓ Email válido';
                            errorElement.style.color = '#2ecc71';
                            errorElement.style.display = 'block';
                        }
                    } else {
                        emailInput.classList.add('invalid');
                        if (errorElement) {
                            errorElement.textContent = validation.message;
                            errorElement.style.color = '#e74c3c';
                            errorElement.style.display = 'block';
                        }
                    }
                }
            }, 500);
        });

        // Validação no envio do formulário
        const form = emailInput.closest('form');
        if (form) {
            form.addEventListener('submit', async (e) => {
                const email = emailInput.value.trim();
                
                if (email) {
                    const validation = await this.validateEmail(email);
                    
                    if (!validation.valid) {
                        e.preventDefault();
                        alert(validation.message);
                        emailInput.focus();
                        return false;
                    }
                }
            });
        }
    }
}

// Função para inicializar validação em todas as páginas
document.addEventListener('DOMContentLoaded', () => {
    // Configurar validação para campo de email
    EmailValidator.setupEmailValidation('email', 'email-error');
});

// Função auxiliar para verificar se email já existe no banco
async function checkEmailExists(email, excludeId = null) {
    try {
        const formData = new FormData();
        formData.append('action', 'check_email');
        formData.append('email', email);
        if (excludeId) {
            formData.append('exclude_id', excludeId);
        }

        const response = await fetch('../../api/check_email.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        return result.exists;
    } catch (error) {
        console.error('Erro ao verificar email:', error);
        return false;
    }
}