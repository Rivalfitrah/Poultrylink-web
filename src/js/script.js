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

 // JavaScript untuk tab switching
 function openTab(evt, tabName) {
  const tabContent = document.querySelectorAll(".tab-content");
  tabContent.forEach(content => content.classList.add("hidden"));

  const tabLinks = document.querySelectorAll(".tab-link");
  tabLinks.forEach(link =>
    link.classList.remove("border-green-600", "text-green-600")
  );

  document.getElementById(tabName).classList.remove("hidden");
  evt.currentTarget.classList.add("border-green-600", "text-green-600");
}

// JavaScript untuk menangani pengiriman review
function submitReview(event) {
  event.preventDefault();
  const name = document.getElementById("reviewerName").value;
  const comment = document.getElementById("reviewComment").value;
  const rating = document.querySelector('input[name="rating"]:checked')?.value || 0;

  const reviewList = document.getElementById("reviewList");
  const newReview = document.createElement("li");
  newReview.classList.add("border-b", "pb-4", "mt-4");
  newReview.innerHTML = `
    <p class="text-gray-800 font-medium">${name}</p>
    <p class="text-yellow-400">${"★".repeat(rating)}${"☆".repeat(5 - rating)}</p>
    <p class="text-sm text-gray-600">${comment}</p>
  `;
  reviewList.appendChild(newReview);

  // Reset form
  document.getElementById("reviewForm").reset();
}

document.getElementById("defaultTab").click();




 // Hamburger Menu Toggle
 const hamburgerBtn = document.getElementById('hamburger-btn');
 const navLinks = document.getElementById('nav-links');
 hamburgerBtn.addEventListener('click', () => {
     navLinks.classList.toggle('hidden');
 });

 // Account Dropdown Toggle
 const accountIcon = document.getElementById('account-icon');
 const accountDropdown = document.getElementById('account-dropdown');
 document.addEventListener('click', (event) => {
     if (!accountDropdown.contains(event.target) && !accountIcon.contains(event.target)) {
         accountDropdown.classList.add('hidden');
     } else if (accountIcon.contains(event.target)) {
         accountDropdown.classList.toggle('hidden');
     }
 });

 // Tab Navigation
 function openTab(event, tabId) {
     const contents = document.querySelectorAll('.tab-content');
     contents.forEach((content) => content.classList.add('hidden'));

     document.getElementById(tabId).classList.remove('hidden');

     const tabs = document.querySelectorAll('.tab-link');
     tabs.forEach((tab) => tab.classList.remove('border-b-2', 'text-green-600'));
     event.currentTarget.classList.add('border-b-2', 'text-green-600');
 }

 // Default Tab
 document.getElementById('defaultTab').click();

 // Review Submission
 function submitReview() {
     const comment = document.getElementById('reviewComment').value;
     if (comment.trim() === '') {
         alert('Tulis review terlebih dahulu.');
         return;
     }
     alert('Review Anda berhasil dikirim!');
     document.getElementById('reviewComment').value = '';
 }