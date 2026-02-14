import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/api/v2',
    withCredentials: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    }
});

// CSRF Protection
api.interceptors.request.use(async (config) => {
    if (['post', 'put', 'delete', 'patch'].includes(config.method)) {
        // Only fetch CSRF cookie if not already set
        if (!document.cookie.includes('XSRF-TOKEN')) {
            await axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
        }
    }
    return config;
});

// Error Handling
api.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            // Handle unauthorized - maybe redirect to login or clear store
            console.error('Unauthorized, please login again.');
        }
        return Promise.reject(error);
    }
);

export default api;
