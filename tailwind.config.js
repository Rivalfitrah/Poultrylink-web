/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js,jsx,ts,tsx}",   // File di folder src
    "./public/**/*.html",                 // File HTML di public
    "./public/admin/**/*.html",           // File HTML di folder admin
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#FF6600',
        'second' : '#FFBF78'
      },
      fontFamily: {
        bauhause: ['Bauhause', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
