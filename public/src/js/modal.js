document.addEventListener("DOMContentLoaded", function () {
  // Modal Toggle
  function toggleModal() {
    const modal = document.getElementById("modal");
    if (modal) modal.classList.toggle("hidden");
  }

  // Profile Dropdown Toggle
  const profileButton = document.getElementById("profileButton");
  const profileDropdown = document.getElementById("profileDropdown");

  if (profileButton && profileDropdown) {
    profileButton.addEventListener("click", function () {
      profileDropdown.classList.toggle("hidden");
    });
  }

  // Open Edit Modal with Pre-filled Data
  function openEditModal(nama, deskripsi, harga, kategori) {
    const editModal = document.getElementById("editModal");
    if (editModal) {
      document.getElementById("edit_nama").value = nama;
      document.getElementById("edit_deskripsi").value = deskripsi;
      document.getElementById("edit_harga").value = harga;
      document.getElementById("edit_kategori").value = kategori;
      editModal.classList.remove("hidden");
    }
  }

  // Close Edit Modal
  function closeEditModal() {
    const editModal = document.getElementById("editModal");
    if (editModal) editModal.classList.add("hidden");
  }

  // Confirm Deletion
  function confirmDelete() {
    if (confirm("Apakah Anda yakin ingin menghapus produk ini?")) {
      alert("Produk telah dihapus.");
    }
  }

  // Attach openEditModal and closeEditModal to the global scope
  window.openEditModal = openEditModal;
  window.closeEditModal = closeEditModal;
  window.confirmDelete = confirmDelete;
  window.toggleModal = toggleModal;
});




// modal profil
      // Toggle Modal
      function toggleModal() {
        const modal = document.getElementById('modal');
        modal.classList.toggle('hidden');
      }
