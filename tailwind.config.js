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
            colors: {
                // Brand primary: emerald (growth / income). Used for CTAs, links, active states.
                primary: {
                    50: '#ecfdf5',
                    100: '#d1fae5',
                    200: '#a7f3d0',
                    300: '#6ee7b7',
                    400: '#34d399',
                    500: '#10b981',
                    600: '#059669',
                    700: '#047857',
                    800: '#065f46',
                    900: '#064e3b',
                    950: '#022c22',
                },
                // Secondary accent: teal. Used sparingly for secondary emphasis.
                accent: {
                    50: '#f0fdfa',
                    100: '#ccfbf1',
                    300: '#5eead4',
                    400: '#2dd4bf',
                    500: '#14b8a6',
                    600: '#0d9488',
                    700: '#0f766e',
                },
            },
            boxShadow: {
                'brand': '0 8px 24px -8px rgba(5, 150, 105, 0.35)',
                'brand-lg': '0 16px 40px -12px rgba(5, 150, 105, 0.4)',
            },
        },
    },

    plugins: [forms],
};