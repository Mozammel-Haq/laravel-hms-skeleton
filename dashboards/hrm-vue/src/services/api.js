import axios from 'axios';

const api = axios.create({
    baseURL: 'http://mozammel.intelsofts.com/hms/public/api/v2',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    }
});

api.interceptors.request.use(async (config) => {
    const token = localStorage.getItem('hrm_token') || sessionStorage.getItem('hrm_token');
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
});

// Error Handling
api.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            localStorage.removeItem('hrm_token');
            sessionStorage.removeItem('hrm_token');
            console.warn('Unauthorized: token cleared');
        }
        return Promise.reject(error);
    }
);

export default api;
