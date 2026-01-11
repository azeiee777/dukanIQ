// tailwind.config.js
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // <--- IMPORTANT: Add this line
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            // Optional: Define premium brand colors here
            colors: {
                primary: {
                    50: '#eef2ff',
                    500: '#6366f1',
                    600: '#4f46e5',
                    900: '#312e81',
                }
            }
        },
    },

    plugins: [forms],
};