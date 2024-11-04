document.addEventListener('DOMContentLoaded', () => {
    const productCard = document.getElementById('product-card');
    
    fetch('https://fakestoreapi.com/products/1')
      .then(response => response.json())
      .then(data => {
        // Update card dengan data dari API
        document.getElementById('product-image').src = data.image;
        document.getElementById('product-name').textContent = data.title; // Mengambil judul produk
        document.getElementById('product-price').textContent = `Rp${data.price}`; // Mengambil harga
        document.getElementById('product-rating').textContent = '★'.repeat(Math.floor(data.rating.rate)) + '☆'.repeat(5 - Math.floor(data.rating.rate)); // Menampilkan rating
        document.getElementById('product-reviews').textContent = `(${data.rating.count} Reviews)`; // Menampilkan jumlah review
        document.getElementById('product-location').textContent = 'Jakarta, Indonesia'; // Mengisi lokasi
      })
      .catch(error => console.error('Error fetching data:', error));
  });
  