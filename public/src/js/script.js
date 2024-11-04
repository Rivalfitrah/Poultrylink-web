document.addEventListener("DOMContentLoaded", () => {
  let currentIndex = 0;
  const slides = document.querySelectorAll(".slide");
  const totalSlides = slides.length;

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

  function showNextSlide() {
    showSlide(currentIndex + 1);
  }

  function showPrevSlide() {
    showSlide(currentIndex - 1);
  }

  const nextBtn = document.getElementById("nextBtn");
  const prevBtn = document.getElementById("prevBtn");

  if (nextBtn) {
    nextBtn.addEventListener("click", showNextSlide);
  }
  if (prevBtn) {
    prevBtn.addEventListener("click", showPrevSlide);
  }

  setInterval(showNextSlide, 5000);
});

// dropdown dashboard
const profileButton = document.getElementById("profileButton");
const profileDropdown = document.getElementById("profileDropdown");

if (profileButton) {
  profileButton.addEventListener("click", function () {
    profileDropdown.classList.toggle("hidden");
  });
}
document.addEventListener("click", function (event) {
  if (profileButton && profileDropdown && !profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
    profileDropdown.classList.add("hidden");
  }
});


