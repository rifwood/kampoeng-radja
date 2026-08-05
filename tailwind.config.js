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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                caption: ['Be Vietnam Pro', 'sans-serif'],
                'body-md': ['Be Vietnam Pro', 'sans-serif'],
                'body-lg': ['Be Vietnam Pro', 'sans-serif'],
                'headline-lg': ['Plus Jakarta Sans', 'sans-serif'],
                'headline-md': ['Plus Jakarta Sans', 'sans-serif'],
                'headline-sm': ['Plus Jakarta Sans', 'sans-serif'],
                'display-lg': ['Plus Jakarta Sans', 'sans-serif'],
                'label-bold': ['Be Vietnam Pro', 'sans-serif'],
            },
            colors: {
                background: '#f8f9fa', surface: '#f8f9fa', 'surface-container-low': '#f3f4f5',
                'surface-container': '#edeeef', 'surface-container-highest': '#e1e3e4',
                primary: '#003f87', 'primary-container': '#0056b3', 'primary-fixed': '#d7e2ff',
                'primary-fixed-dim': '#acc7ff', 'on-primary': '#ffffff', 'on-primary-container': '#bbd0ff',
                secondary: '#904d00', 'secondary-container': '#fd8b00', 'secondary-fixed': '#ffdcc3',
                'secondary-fixed-dim': '#ffb77d', 'on-secondary-container': '#603100',
                tertiary: '#86003a', 'tertiary-container': '#b1004f', 'tertiary-fixed': '#ffd9df',
                'on-tertiary': '#ffffff', 'on-tertiary-container': '#ffbfcb',
                'on-surface': '#191c1d', 'on-surface-variant': '#424752', outline: '#727784',
                'outline-variant': '#c2c6d4', 'inverse-surface': '#2e3132', 'inverse-primary': '#acc7ff',
            },
            spacing: { gutter: '24px', 'container-max': '1280px', 'section-padding-desktop': '80px', 'margin-lg': '48px' },
            fontSize: {
                caption: ['12px', { lineHeight: '1.4', fontWeight: '500' }],
                'body-md': ['16px', { lineHeight: '1.6', fontWeight: '400' }],
                'body-lg': ['18px', { lineHeight: '1.6', fontWeight: '400' }],
                'headline-lg': ['40px', { lineHeight: '1.2', fontWeight: '700' }],
                'headline-md': ['32px', { lineHeight: '1.3', fontWeight: '700' }],
                'headline-sm': ['24px', { lineHeight: '1.4', fontWeight: '600' }],
                'display-lg': ['56px', { lineHeight: '1.1', letterSpacing: '-.02em', fontWeight: '800' }],
                'label-bold': ['14px', { lineHeight: '1.2', letterSpacing: '.05em', fontWeight: '700' }],
            },
        },
    },

    plugins: [forms],
};
