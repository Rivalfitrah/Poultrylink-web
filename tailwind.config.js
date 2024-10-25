// tailwind.config.js
module.exports = {
  content: [
    "./src/**/*.{html,js,jsx,ts,tsx}",
    "./public/**/*.html",
    "./public/sign/**/*.html",
    "./public/dashboard/**/*.html",
    "/public/akun/**/*.html"
  ],
  theme: {
    extend: {
      colors: {
        primary: '#FF6600',
        second: '#FFBF78',
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
