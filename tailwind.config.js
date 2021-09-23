module.exports = {
    mode: "jit",
    purge: ["./resources/views/*.blade.php", "./resources/**/*.{js,ts,vue}"],
    darkMode: 'class', // or 'media' or 'class'
    theme: {
        extend: {},
    },
    variants: {
        extend: {},
    },
    plugins: [],
};
