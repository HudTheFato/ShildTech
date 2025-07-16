// Sistema de Autenticação e Gerenciamento de Dados
class AuthManager {
    static TOKEN_KEY = 'shieldtech_token';
    static USER_KEY = 'shieldtech_user';
    static SESSION_TIMEOUT = 30 * 60 * 1000; // 30 minutos

    static async login(email, password) {
        try {
            // Simular chamada à API
            const response = await this.mockApiCall(email, password);
            
            if (response.success) {
                this.setSession(response.token, response.user);
                return true;
            }
            return false;
        } catch (error) {
            console.error('Erro no login:', error);
            return false;
        }
    }

    static logout() {
        localStorage.removeItem(this.TOKEN_KEY);
        localStorage.removeItem(this.USER_KEY);
        window.location.href = '/pages/login.html';
    }

    static isAuthenticated() {
        const token = this.getToken();
        if (!token) return false;

        // Verificar se a sessão expirou
        const user = this.getUser();
        if (!user || !user.lastActivity) return false;

        const now = new Date().getTime();
        if (now - user.lastActivity > this.SESSION_TIMEOUT) {
            this.logout();
            return false;
        }

        // Atualizar última atividade
        user.lastActivity = now;
        this.setUser(user);
        return true;
    }

    static getToken() {
        return localStorage.getItem(this.TOKEN_KEY);
    }

    static getUser() {
        const user = localStorage.getItem(this.USER_KEY);
        return user ? JSON.parse(user) : null;
    }

    static setSession(token, user) {
        localStorage.setItem(this.TOKEN_KEY, token);
        user.lastActivity = new Date().getTime();
        localStorage.setItem(this.USER_KEY, JSON.stringify(user));
    }

    static setUser(user) {
        localStorage.setItem(this.USER_KEY, JSON.stringify(user));
    }

    // Simulação de chamada à API
    static async mockApiCall(email, password) {
        return new Promise((resolve) => {
            setTimeout(() => {
                // Simular validação básica
                if (email === 'admin@shieldtech.com' && password === 'admin123') {
                    resolve({
                        success: true,
                        token: 'mock-jwt-token',
                        user: {
                            id: 1,
                            name: 'Administrador',
                            email: email,
                            role: 'admin'
                        }
                    });
                } else {
                    resolve({ success: false });
                }
            }, 1000);
        });
    }
}

// Middleware de autenticação
function requireAuth() {
    if (!AuthManager.isAuthenticated()) {
        window.location.href = '/pages/login.html';
        return false;
    }
    return true;
}

// Proteção contra CSRF
function generateCSRFToken() {
    const token = Math.random().toString(36).substring(2);
    sessionStorage.setItem('csrf_token', token);
    return token;
}

function validateCSRFToken(token) {
    const storedToken = sessionStorage.getItem('csrf_token');
    return token === storedToken;
} 