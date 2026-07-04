import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                ink: {
                    DEFAULT: '#1c2b28',
                    muted: '#4a5c58',
                    faint: '#7a8f8a',
                },
                paper: {
                    DEFAULT: '#f6f2ea',
                    warm: '#ede6d9',
                    deep: '#e3d9c8',
                },
                moss: {
                    DEFAULT: '#2d5a4a',
                    light: '#3d7562',
                    dark: '#1e3d32',
                },
                clay: {
                    DEFAULT: '#c06c4a',
                    light: '#d4886a',
                    dark: '#9a5238',
                },
            },
            boxShadow: {
                card: '0 1px 2px rgba(28, 43, 40, 0.04), 0 8px 32px rgba(28, 43, 40, 0.08)',
                lift: '0 4px 24px rgba(28, 43, 40, 0.12)',
            },
            animation: {
                'fade-up': 'fadeUp 0.6s ease-out both',
                'fade-in': 'fadeIn 0.4s ease-out both',
            },
            keyframes: {
                fadeUp: {
                    '0%': { opacity: '0', transform: 'translateY(16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
            },
        },
    },
    plugins: [],
};
