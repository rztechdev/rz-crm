import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

const rzSageGreen = {
    50: '#f6f7f3',
    100: '#e9ede1',
    200: '#d5dec8',
    300: '#bccbaa',
    400: '#a2b187', // RZ Primary (#A2B187)
    500: '#8b9b70', // RZ Deep (#8B9B70 - Main Brand Green from rz - about)
    600: '#7a8a60', // RZ Deep Hover (#7A8A60)
    700: '#64724e',
    800: '#525d40',
    900: '#444d36',
    950: '#252b1d',
    DEFAULT: '#8b9b70',
};

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'Inter', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                // RZ Digital Creative - Exact Sage Green Theme from rz - about
                brand: rzSageGreen,
                emerald: rzSageGreen,
                rz: rzSageGreen,
                zinc: colors.zinc,
            },
            boxShadow: {
                'xs': '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                'card': '0 1px 3px 0 rgba(0, 0, 0, 0.06), 0 1px 2px 0 rgba(0, 0, 0, 0.04)',
                'glow': '0 0 20px -5px rgba(139, 155, 112, 0.35)',
            },
            borderRadius: {
                DEFAULT: '0.625rem',
                'none': '0',
                'sm': 'calc(0.625rem - 4px)', // 6px
                'md': 'calc(0.625rem - 2px)', // 8px
                'lg': '0.625rem',             // 10px (TweakCN exact theme radius)
                'xl': '0.625rem',             // 10px
                '2xl': '0.625rem',            // 10px
                '3xl': '0.75rem',             // 12px
                'full': '9999px',
            }
        },
    },

    plugins: [forms],
};
