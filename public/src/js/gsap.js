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
  