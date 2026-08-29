import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

const rzPalette = {
    50: '#F6F8F3',
    100: '#EAEFE2',
    200: '#D6E0C7',
    300: '#BDCDA8',
    400: '#A2B187', // RZ Primary Green
    500: '#8B9B70', // RZ Deep Green
    600: '#7A8A60', // RZ Deep Hover Green
    700: '#64724E',
    800: '#4F5B3D',
    900: '#3B442D',
    950: '#212719',
    DEFAULT: '#8B9B70',
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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                rz: {
                    primary: '#A2B187',
                    deep: '#8B9B70',
                    'deep-hover': '#7A8A60',
                    cream: '#F9F8F3',
                    'cream-subtle': '#F2F0E6',
                    charcoal: '#2E2E2A',
                    'charcoal-muted': '#595952',
                    'charcoal-dark': '#22221E',
                    ...rzPalette,
                },
                emerald: rzPalette,
                green: rzPalette,
                blue: rzPalette,
                sky: rzPalette,
                indigo: rzPalette,
                purple: rzPalette,
                teal: rzPalette,
                lime: rzPalette,
                gray: colors.zinc,
                slate: colors.zinc,
            },
        },
    },

    plugins: [forms],
};
