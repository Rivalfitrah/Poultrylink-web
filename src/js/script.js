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

// Toggling Dropdown Menu
const accountIcon = document.getElementById("account-icon");
const accountDropdown = document.getElementById("account-dropdown");

accountIcon.addEventListener("click", function () {
  accountDropdown.classList.toggle("hidden");

  if (!accountDropdown.classList.contains("hidden")) {
    accountDropdown.classList.add("opacity-100", "scale-100");
    accountDropdown.classList.remove("opacity-0", "scale-95");
  } else {
    accountDropdown.classList.add("opacity-0", "scale-95");
    accountDropdown.classList.remove("opacity-100", "scale-100");
  }
});

// Klik di luar dropdown untuk menutup
window.addEventListener("click", function (e) {
  if (!accountIcon.contains(e.target)) {
    accountDropdown.classList.add("hidden");
    accountDropdown.classList.add("opacity-0", "scale-95");
    accountDropdown.classList.remove("opacity-100", "scale-100");
  }
});
