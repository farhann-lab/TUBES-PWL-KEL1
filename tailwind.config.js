import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                elco: {
                    dark: '#2A1A14',    
                    coffee: '#3E2723',  
                    mocha: '#5D4037',   
                    latte: '#D7CCC8',   
                    cream: '#FDFBF7',   
                    white: '#FFFFFF',
                }
            },
            boxShadow: {
                'soft': '0 10px 40px -10px rgba(62, 39, 35, 0.05)',
                'hover': '0 20px 40px -10px rgba(62, 39, 35, 0.12)',
            }
        },
    },

    plugins: [forms],
};
