import daisyui from "daisyui";

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {},
    },
    plugins: [
        daisyui,
    ],
    daisyui: {
        themes: ["light --default", "dark", "bumblebee", "cupcake", "wireframe", "coffee", "black", "corporate"], // etc
    }
};
