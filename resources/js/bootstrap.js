import axios from 'axios';

// Make axios globally available
window.axios = axios;

// Default headers
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Get CSRF token from meta tag
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}

// Request interceptor for adding auth token
window.axios.interceptors.request.use(
    (config) => {
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor for handling errors
window.axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response) {
            switch (error.response.status) {
                case 401:
                    // Redirect to login if unauthorized
                    window.location.href = '/login';
                    break;
                case 403:
                    console.error('Access forbidden');
                    break;
                case 419:
                    // CSRF token mismatch - refresh page
                    window.location.reload();
                    break;
                case 422:
                    // Validation errors - let calling code handle
                    break;
                case 500:
                    console.error('Server error');
                    break;
            }
        }
        return Promise.reject(error);
    }
);
