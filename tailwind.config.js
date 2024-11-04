// tailwind.config.js
module.exports = {
  content: [
    "./public/**/*.html",
    "./public/src/**/*.{js,css,jsx,ts,tsx}", // Tangkap file JS, CSS, JSX, dll.
  ],  
  theme: {
    extend: {
      colors: {
        primary: '#FF6600',
        second: '#3b65ed',
      },
      fontFamily: {
        bauhause: ['Bauhause', 'sans-serif'],
      },
    },
  },
  plugins: [],
  watchOptions: {
    ignored: ['C:\\DumpStack.log.tmp'], // Tambahkan path ini agar diabaikan
  },
};