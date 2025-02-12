import forms from '@tailwindcss/forms'
import typo from '@tailwindcss/typography'

/** @type {import('tailwindcss').Config} */
export default {
    content: [
		"./resources/**/*.blade.php",
		 "./resources/**/*.js",
		 "./resources/**/*.vue",
		 "./vendor/robsontenorio/mary/src/View/Components/**/*.php",

        //add to mary
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
	],
    theme: {
        extend: {},
    },
    plugins: [
		require("daisyui")
	],
}

