// tailwind.config.js
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
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
                // "Aurora" design system — dark-first, gradient-forward.
                // Neutral scale (cool violet-black undertone). 50 = lightest (light-mode
                // surfaces), 950 = near-black page background in dark mode.
                ink: {
                    50: '#F7F7FB',
                    100: '#EFEFF7',
                    200: '#E3E3EF',
                    300: '#C9C9DC',
                    400: '#9494A6',
                    500: '#6B6B80',
                    600: '#4A4A5E',
                    700: '#2E2E3E',
                    750: '#242432',
                    800: '#1E1E2A',
                    850: '#171720',
                    900: '#12121C',
                    950: '#0B0B14',
                },
                // Income / positive. A brighter, more saturated green than the old
                // "primary" emerald — reads as data, not just brand color.
                mint: {
                    50: '#EFFDF8',
                    100: '#D6FAEE',
                    300: '#6EE7C8',
                    400: '#22D3AA',
                    500: '#0FB894',
                    600: '#0B9578',
                },
                // Named aliases for the signature 3-stop gradient, used sparingly for
                // hero moments (primary CTAs, active nav state, key data viz) — not
                // every interactive element. Everyday interactive color is violet.
                aurora: {
                    violet: '#7C3AED',
                    fuchsia: '#D946EF',
                    amber: '#F59E0B',
                },
            },
            backgroundImage: {
                'aurora-gradient': 'linear-gradient(135deg, #7C3AED 0%, #D946EF 55%, #F59E0B 100%)',
                'aurora-gradient-soft': 'linear-gradient(135deg, rgba(124,58,237,0.15) 0%, rgba(217,70,239,0.15) 55%, rgba(245,158,11,0.15) 100%)',
            },
            boxShadow: {
                'glow-violet': '0 8px 30px -8px rgba(124, 58, 237, 0.45)',
                'glow-violet-lg': '0 20px 50px -12px rgba(124, 58, 237, 0.5)',
                'glass': '0 8px 32px -8px rgba(11, 11, 20, 0.35)',
            },
        },
    },

    plugins: [forms],
};
