// Sistema de Gerenciamento de Dados
class DataManager {
    static PREFIX = 'shieldtech_';

    static saveData(key, data) {
        try {
            localStorage.setItem(this.PREFIX + key, JSON.stringify(data));
            return true;
        } catch (error) {
            console.error('Erro ao salvar dados:', error);
            return false;
        }
    }

    static getData(key) {
        try {
            const data = localStorage.getItem(this.PREFIX + key);
            return data ? JSON.parse(data) : null;
        } catch (error) {
            console.error('Erro ao recuperar dados:', error);
            return null;
        }
    }

    static removeData(key) {
        try {
            localStorage.removeItem(this.PREFIX + key);
            return true;
        } catch (error) {
            console.error('Erro ao remover dados:', error);
            return false;
        }
    }

    static clearAll() {
        try {
            Object.keys(localStorage)
                .filter(key => key.startsWith(this.PREFIX))
                .forEach(key => localStorage.removeItem(key));
            return true;
        } catch (error) {
            console.error('Erro ao limpar dados:', error);
            return false;
        }
    }
}

// Gerenciador de Cache
class CacheManager {
    static CACHE_PREFIX = 'shieldtech_cache_';
    static DEFAULT_EXPIRATION = 5 * 60 * 1000; // 5 minutos

    static set(key, value, expiration = this.DEFAULT_EXPIRATION) {
        const item = {
            value,
            expires: Date.now() + expiration
        };
        localStorage.setItem(this.CACHE_PREFIX + key, JSON.stringify(item));
    }

    static get(key) {
        const item = localStorage.getItem(this.CACHE_PREFIX + key);
        if (!item) return null;

        const data = JSON.parse(item);
        if (Date.now() > data.expires) {
            localStorage.removeItem(this.CACHE_PREFIX + key);
            return null;
        }

        return data.value;
    }

    static clear() {
        Object.keys(localStorage)
            .filter(key => key.startsWith(this.CACHE_PREFIX))
            .forEach(key => localStorage.removeItem(key));
    }
}

// Gerenciador de Estado
class StateManager {
    static state = {};
    static listeners = new Map();

    static setState(key, value) {
        this.state[key] = value;
        this.notifyListeners(key);
    }

    static getState(key) {
        return this.state[key];
    }

    static subscribe(key, callback) {
        if (!this.listeners.has(key)) {
            this.listeners.set(key, new Set());
        }
        this.listeners.get(key).add(callback);
    }

    static unsubscribe(key, callback) {
        if (this.listeners.has(key)) {
            this.listeners.get(key).delete(callback);
        }
    }

    static notifyListeners(key) {
        if (this.listeners.has(key)) {
            this.listeners.get(key).forEach(callback => callback(this.state[key]));
        }
    }
} 