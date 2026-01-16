import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // New color palette: Yellow-Orange-Green
                'brand': {
                    'yellow': '#FFD700',
                    'orange': '#FF8C00',
                    'green': '#32CD32',
                    'lime': '#9ACD32',
                },
                'app': {
                    'dark': '#0a0a0a',
                    'darker': '#050505',
                    'gray': '#1a1a1a',
                    'light-gray': '#2a2a2a',
                },
            },
        },
    },

    plugins: [forms],
};
