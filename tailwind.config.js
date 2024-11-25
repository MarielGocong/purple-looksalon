const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./node_modules/flowbite/**/*.js",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                salonPurple: '#6a2695', // salon color
                secondary: '#9667b4',
            },
        },
    },

    safelist: [
        ...[...Array(10).keys()].flatMap(i => [`top-[${i*10}%]`, `left-[${i*10}%]`])
    ],

    plugins: [
        require('flowbite/plugin')({
            charts: true,
        }),
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
        require('@tailwindcss/aspect-ratio'),
    ],
};
