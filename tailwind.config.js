import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
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
                surface: {
                    DEFAULT: '#f6f8fb',
                    card: '#ffffff',
                },
                accent: {
                    DEFAULT: '#0891b2',
                    hover: '#2563eb',
                    soft: '#ecfeff',
                    mid: '#38bdf8',
                },
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.25rem',
            },
            boxShadow: {
                card: '0 1px 2px 0 rgb(15 23 42 / 0.04), 0 0 0 1px rgb(15 23 42 / 0.04)',
                'card-md': '0 4px 16px -2px rgb(15 23 42 / 0.08), 0 0 0 1px rgb(15 23 42 / 0.04)',
                hero: '0 24px 48px -12px rgb(6 182 212 / 0.35)',
                brand: '0 4px 14px -2px rgb(6 182 212 / 0.4)',
            },
        },
    },

    plugins: [forms],
};
