import axios from 'axios';
import { useToastStore } from '../store/toastStore';

const api = axios.create({
    baseURL: '/api/v2',
    withCredentials: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    }
});

api.interceptors.request.use(async (config) => {
    const token = sessionStorage.getItem('hrm_token');
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
});

api.interceptors.response.use(
    response => {
        try {
            const method = (response?.config?.method || '').toLowerCase();
            const skip = response?.config?.headers?.['X-Skip-Toast'];
            if (!skip && ['post', 'put', 'patch', 'delete'].includes(method)) {
                const toast = useToastStore();
                const url = (response?.config?.url || '').split('?')[0];
                const segments = url.split('/').filter(Boolean);
                const tail = segments[segments.length - 1] || '';
                const resource = tail.match(/^\d+$/) ? segments[segments.length - 2] || 'item' : tail || 'item';
                const pretty = resource.replace(/[-_]/g, ' ');
                const cap = pretty.charAt(0).toUpperCase() + pretty.slice(1);
                const action =
                    method === 'post' ? 'created'
                    : method === 'delete' ? 'deleted'
                    : 'updated';
                const msg = response?.data?.message || `${cap} ${action}`;
                toast.success(msg);
            }
        } catch (_) {}
        return response;
    },
    error => {
        if (error.response && error.response.status === 401) {
            sessionStorage.removeItem('hrm_token');
            console.warn('Unauthorized: token cleared');
        }
        try {
            const skip = error?.config?.headers?.['X-Skip-Toast'];
            if (!skip) {
                const toast = useToastStore();
                const msg = error?.response?.data?.message
                    || error?.message
                    || 'Operation failed';
                toast.error(String(msg));
            }
        } catch (_) {}
        return Promise.reject(error);
    }
);

export default api;
