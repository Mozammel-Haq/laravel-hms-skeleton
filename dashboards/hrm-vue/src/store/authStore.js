import { defineStore } from 'pinia';
import api from '../services/api';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        loading: false,
        error: null,
        initialized: false,
    }),

    actions: {
        async fetchUser() {
            this.loading = true;
            try {
                const response = await api.get('/me');
                this.user = response.data.data;
                this.initialized = true;
            } catch (error) {
                this.user = null;
                this.error = error.response?.data?.message || 'Failed to fetch user';
            } finally {
                this.loading = false;
            }
        },

        async login(credentials) {
            this.loading = true;
            try {
                // Ensure CSRF cookie is set
                await api.get('/csrf-cookie', { baseURL: 'http://localhost:8000/sanctum' });
                
                const response = await api.post('/login', credentials);
                this.user = response.data.user;
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Login failed';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            try {
                await api.post('/logout');
                this.user = null;
            } catch (error) {
                console.error('Logout failed', error);
            }
        }
    }
});
