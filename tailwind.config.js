import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                surface: 'rgb(var(--color-surface) / <alpha-value>)',
                'surface-container': 'rgb(var(--color-surface-container) / <alpha-value>)',
                'surface-container-low': 'rgb(var(--color-surface-container-low) / <alpha-value>)',
                'surface-container-high': 'rgb(var(--color-surface-container-high) / <alpha-value>)',
                'surface-deep': 'rgb(var(--color-surface-deep) / <alpha-value>)',

                primary: 'rgb(var(--color-primary) / <alpha-value>)',
                'on-primary': 'rgb(var(--color-on-primary) / <alpha-value>)',
                secondary: 'rgb(var(--color-secondary) / <alpha-value>)',
                'on-secondary': 'rgb(var(--color-on-secondary) / <alpha-value>)',
                tertiary: 'rgb(var(--color-tertiary) / <alpha-value>)',
                'on-tertiary': 'rgb(var(--color-on-tertiary) / <alpha-value>)',

                accent: 'rgb(var(--color-accent) / <alpha-value>)',
                'on-accent': 'rgb(var(--color-on-accent) / <alpha-value>)',
                'on-surface': 'rgb(var(--color-on-surface) / <alpha-value>)',
                'on-surface-variant': 'rgb(var(--color-on-surface-variant) / <alpha-value>)',
                outline: 'rgb(var(--color-outline) / <alpha-value>)',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                header: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                '2xl': '1rem',
            },
            screens: {
                xs: '480px',
            },
        },
    },

    plugins: [forms],
};
