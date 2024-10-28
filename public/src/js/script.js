let currentIndex = 0;
const slides = document.querySelectorAll(".slide");
const totalSlides = slides.length;

// Fungsi untuk menampilkan slide berdasarkan indeks
function showSlide(index) {
  if (index >= totalSlides) {
    currentIndex = 0;
  } else if (index < 0) {
    currentIndex = totalSlides - 1;
  } else {
    currentIndex = index;
  }
  document.getElementById("slider").style.transform = `translateX(-${
    currentIndex * 100
  }%)`;
}

// Fungsi untuk menampilkan slide berikutnya
function showNextSlide() {
  showSlide(currentIndex + 1);
}

// Fungsi untuk menampilkan slide sebelumnya
function showPrevSlide() {
  showSlide(currentIndex - 1);
}

// Event listener untuk tombol Next dan Prev
document.getElementById("nextBtn").addEventListener("click", showNextSlide);
document.getElementById("prevBtn").addEventListener("click", showPrevSlide);

// Pindah slide otomatis setiap 5 detik
setInterval(showNextSlide, 5000);


  // Toggle untuk mobile menu
  const menuToggle = document.getElementById('menu-toggle');
  const mobileMenu = document.getElementById('mobile-menu');

  menuToggle.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });




