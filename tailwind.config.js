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
                sans: ['"Work Sans"', ...defaultTheme.fontFamily.sans],
                serif: ['Newsreader', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                paper: '#EDE7DA',
                ink: '#2B2A28',
                accent: '#3B5249',
                muted: '#8C8577',
                line: '#C9BFA9',
            },
            height: {
                dvh: '100dvh',
            },
            width: {
                dvw: '100dvw',
            },
        },
    },

    plugins: [forms],
};