// window.addEventListener('load', () => {
//     // Animasi untuk memisahkan background dari tengah
//     gsap.to(".left", { duration: 1, x: "-100%", ease: "power2.out" });
//     gsap.to(".right", { duration: 1, x: "100%", ease: "power2.out", onComplete: () => {
//       document.getElementById("loading-screen").style.display = "none";
//       document.getElementById("content").style.display = "block";
//       gsap.from("#content", { duration: 5, opacity: 0, y: 50 });
//     }});
//   });

// GSAP Bouncing Animation for Loading Text
// GSAP Animation for Spinning Circle
gsap.to("#loading-circle", {
  rotation: 360,     // Rotasi penuh 360 derajat
  repeat: -1,        // Ulangi tanpa batas
  ease: "linear",    // Rotasi konstan
  duration: 1        // Durasi satu putaran
});

// Sembunyikan loading screen dan tampilkan halaman utama setelah 3 detik
setTimeout(() => {
  gsap.to("#loading-screen", {
    opacity: 0,         // Menghilangkan loading screen
    duration: 0.5,      // Durasi animasi opacity
    onComplete: () => {
      document.getElementById("loading-screen").style.display = "none"; // Sembunyikan elemen loading
      document.getElementById("main-content").style.display = "block";  // Tampilkan konten utama
    }
  });
}, 500); // Ubah durasi (3000 ms = 3 detik) sesuai kebutuhan

