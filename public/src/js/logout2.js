$(document).ready(function() {
    const base_url = window.location.origin;
    $('#logout-btn').on('click', function(event) {
        event.preventDefault(); // Mencegah link melakukan aksi default
  
        // Konfirmasi sebelum logout (opsional)
        Swal.fire({
            title: 'Apakah Kamu yakin?',
            text: "Kamu akan Keluar!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Keluar!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ambil token autentikasi yang ada di localStorage
                const token = localStorage.getItem('supplier_token');
  
                // Kirim permintaan API untuk logout
                $.ajax({
                    url: 'https://poultrylink.ambatuwin.xyz/api/logoutsupplier',  // Ganti dengan URL API logout yang sesuai
                    type: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`  // Kirim token untuk autentikasi
                    },
                    success: function(response) {
                        // Hapus data di localStorage setelah logout berhasil
                        localStorage.removeItem('supplier_token');
                        localStorage.removeItem('isLoggedIn');  // Pastikan status login juga dihapus
  
                        // Berikan notifikasi logout berhasil
                        Swal.fire(
                            'Logged Out!',
                            'Kamu Berhasil keluar',
                            'success'
                        ).then(() => {
                            // Redirect ke halaman login setelah logout
                            window.location.href = base_url + '/poultrylink/public/home.html';
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