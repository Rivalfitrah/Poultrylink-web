// JavaScript for switching between tabs
const descriptionTab = document.getElementById('description-tab');
const reviewsTab = document.getElementById('reviews-tab');
const descriptionContent = document.getElementById('description-content');
const reviewsContent = document.getElementById('reviews-content');

descriptionTab.addEventListener('click', function () {
  descriptionContent.classList.remove('hidden');
  reviewsContent.classList.add('hidden');
  descriptionTab.classList.add('border-b-2', 'border-primary', 'text-gray-700');
  reviewsTab.classList.remove('border-b-2', 'border-primary', 'text-gray-700');
  reviewsTab.classList.add('text-gray-500');
});

reviewsTab.addEventListener('click', function () {
  reviewsContent.classList.remove('hidden');
  descriptionContent.classList.add('hidden');
  reviewsTab.classList.add('border-b-2', 'border-primary', 'text-gray-700');
  descriptionTab.classList.remove('border-b-2', 'border-primary', 'text-gray-700');
  descriptionTab.classList.add('text-gray-500');
});

// JavaScript for quantity increment/decrement
document.getElementById('increase-quantity').addEventListener('click', function () {
  let quantityInput = document.getElementById('quantity');
  let currentValue = parseInt(quantityInput.value);
  quantityInput.value = currentValue + 1;
});

document.getElementById('decrease-quantity').addEventListener('click', function () {
  let quantityInput = document.getElementById('quantity');
  let currentValue = parseInt(quantityInput.value);
  if (currentValue > 1) {
    quantityInput.value = currentValue - 1;
  }
});