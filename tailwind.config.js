// tailwind.config.js
module.exports = {
  content: [
    "./public/**/*.html",               // Mendeteksi semua HTML di folder public dan subfoldernya
    "./public/src/**/*.{js,css}",        // Mendeteksi semua JavaScript dan CSS di dalam public/src
    // "./public/src/**/*.{jsx,ts,tsx}",
    // "./public/sign/**/*.html",
    // "./public/dashboard/**/*.html",
    // "./public/akun/**/*.html"
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
