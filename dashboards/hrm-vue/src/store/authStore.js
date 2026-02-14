import { defineStore } from 'pinia';
import api from '../services/api';
import axios from 'axios';

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
            } catch (error) {
                if (error.response?.status === 401) {
                    this.user = null;
                    this.error = null;
                    localStorage.removeItem('hrm_token');
                } else {
                    this.user = null;
                    this.error = error.response?.data?.message || 'Failed to fetch user';
                }
            } finally {
                this.initialized = true;
                this.loading = false;
            }
        },

        async login(credentials) {
            this.loading = true;
            try {
                // API token-based login, no redirect to web dashboard
                const res = await api.post('/login', credentials);
                const token = res.data.token;
                if (token) {
                    localStorage.setItem('hrm_token', token);
                }
                const me = await api.get('/me');
                this.user = me.data.data;
                return { user: this.user, token };
            } catch (error) {
                this.error = error.response?.data?.message || 'Login failed';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            try {
                // Try to inform the backend; ignore failure (token may be invalid/expired)
                await api.post('/logout');
            } catch (error) {
                console.error('Logout failed', error);
            } finally {
                // Always clear local auth state
                this.user = null;
                localStorage.removeItem('hrm_token');
            }
        }
    }
});
