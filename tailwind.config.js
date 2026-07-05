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
                    DEFAULT: '#152220',
                    muted: '#3d524e',
                    faint: '#6b807b',
                },
                paper: {
                    DEFAULT: '#f4f0e8',
                    warm: '#ebe4d6',
                    deep: '#ddd3c2',
                },
                moss: {
                    DEFAULT: '#1f4d3c',
                    light: '#2a6650',
                    dark: '#143028',
                    glow: '#3d8a6a',
                },
                clay: {
                    DEFAULT: '#c46840',
                    light: '#de8560',
                    dark: '#9a4e2e',
                },
                cream: '#faf7f2',
            },
            boxShadow: {
                card: '0 1px 2px rgba(21, 34, 32, 0.04), 0 12px 40px rgba(21, 34, 32, 0.08)',
                lift: '0 20px 50px rgba(21, 34, 32, 0.14)',
                glow: '0 0 0 1px rgba(255,255,255,0.08), 0 24px 60px rgba(0,0,0,0.35)',
                inner: 'inset 0 1px 0 rgba(255,255,255,0.06)',
            },
            backgroundImage: {
                'hero-gradient': 'linear-gradient(145deg, #143028 0%, #1f4d3c 42%, #2a6650 100%)',
                'mesh-light': 'radial-gradient(at 20% 20%, rgba(61,138,106,0.12) 0, transparent 50%), radial-gradient(at 80% 0%, rgba(196,104,64,0.08) 0, transparent 45%)',
            },
            animation: {
                'fade-up': 'fadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) both',
                'fade-in': 'fadeIn 0.5s ease-out both',
                'float': 'float 6s ease-in-out infinite',
            },
            keyframes: {
                fadeUp: {
                    '0%': { opacity: '0', transform: 'translateY(24px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-8px)' },
                },
            },
        },
    },
    plugins: [],
    safelist: [
        'category-tile--home',
        'category-tile--desk',
        'category-tile--outdoor',
        'product-thumb--home',
        'product-thumb--desk',
        'product-thumb--outdoor',
    ],
};
