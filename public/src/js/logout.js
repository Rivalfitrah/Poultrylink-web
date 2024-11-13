$(document).ready(function() {
    const base_url = window.location.origin;
    $('#logout-btn').on('click', function(event) {
        event.preventDefault(); // Mencegah link melakukan aksi default
  
        // Konfirmasi sebelum logout (opsional)
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, logout!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ambil token autentikasi yang ada di localStorage
                const token = localStorage.getItem('auth_token');
  
                // Kirim permintaan API untuk logout
                $.ajax({
                    url: 'http://127.0.0.1:8000/api/logout',  // Ganti dengan URL API logout yang sesuai
                    type: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`  // Kirim token untuk autentikasi
                    },
                    success: function(response) {
                        // Hapus data di localStorage setelah logout berhasil
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('isLoggedIn');  // Pastikan status login juga dihapus
  
                        // Berikan notifikasi logout berhasil
                        Swal.fire(
                            'Logged Out!',
                            'You have been successfully logged out.',
                            'success'
                        ).then(() => {
                            // Redirect ke halaman login setelah logout
                            window.location.href = base_url + '/poultrylink/frontend1/public/index.html';
                        });
                    },
                    error: function(xhr, status, error) {
                        // Jika ada error, tampilkan notifikasi kesalahan
                        Swal.fire(
                            'Error!',
                            'Something went wrong. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    });
  });