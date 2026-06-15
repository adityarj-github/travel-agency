import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                display: ['"Playfair Display"', 'serif'],
            },
            colors: {
                brand: {
                    50: '#eefdfb',
                    100: '#d4f8f2',
                    200: '#aef0e7',
                    300: '#76e2d6',
                    400: '#37cbbd',
                    500: '#16b1a4',
                    600: '#0d8e85',
                    700: '#10726c',
                    800: '#125b58',
                    900: '#144c49',
                    950: '#052e2d',
                },
                forest: {
                    50: '#f4f6f2',
                    100: '#e5e9df',
                    200: '#cbd4bf',
                    300: '#a8b899',
                    400: '#82986f',
                    500: '#647a52',
                    600: '#4e6140',
                    700: '#3e4d34',
                    800: '#333f2d',
                    900: '#2b3527',
                    950: '#161c14',
                },
                sand: {
                    50: '#faf6f1',
                    100: '#f2e9dd',
                    200: '#e6d2ba',
                    300: '#d6b48f',
                    400: '#c79468',
                    500: '#bd7e4f',
                    600: '#af6943',
                    700: '#92533a',
                    800: '#774434',
                    900: '#61392d',
                },
            },
            container: {
                center: true,
                padding: {
                    DEFAULT: '1rem',
                    sm: '1.5rem',
                    lg: '2rem',
                },
                // Cap the desktop layout at 1200px so content never stretches
                // edge-to-edge on large monitors, while staying fluid below.
                screens: {
                    sm: '640px',
                    md: '768px',
                    lg: '1024px',
                    xl: '1200px',
                    '2xl': '1200px',
                },
            },
        },
    },
    plugins: [],
};
