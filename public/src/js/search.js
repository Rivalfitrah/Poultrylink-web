$(document).ready(function () {
    var $searchInput = $('#searchInput');
    var $searchResult = $('#searchResult');
    var debounceTimeout;

    // Fungsi untuk menyembunyikan hasil pencarian
    function clearSearchResults() {
        $searchResult.empty(); // Kosongkan hasil pencarian
        $searchResult.hide(); // Sembunyikan hasil pencarian
    }

    // Event listener untuk input pencarian dengan debouncing
    $searchInput.on('input', function () {
        var query = $searchInput.val().trim();

        // Hapus timeout sebelumnya jika ada
        clearTimeout(debounceTimeout);

        // Set timeout untuk menghindari permintaan yang berlebihan
        debounceTimeout = setTimeout(function () {
            // Jika input kosong, sembunyikan hasil pencarian
            if (query === '') {
                clearSearchResults(); // Kosongkan dan sembunyikan hasil pencarian
                return;
            }

            // Jika input tidak kosong, lakukan AJAX
            $.ajax({
                url: "https://poultrylink.ambatuwin.xyz/api/produkall",
                type: "GET",
                success: function (response) {
                    var produks = response.data;

                    // Kosongkan hasil sebelumnya
                    $searchResult.empty();

                    // Filter produk berdasarkan query pencarian
                    var filteredProduks = produks.filter(function (produk) {
                        return produk.nama_produk.toLowerCase().includes(query.toLowerCase());
                    });

                    // Menampilkan hasil pencarian
                    if (filteredProduks.length > 0) {
                        $.each(filteredProduks, function (i, produk) {
                            const imageUrl = produk.id
                                ? `https://hbssyluucrwsbfzspyfp.supabase.co/storage/v1/object/products/${produk.id}/1.jpg`
                                : './path/to/default-image.jpg';

                        $searchResult.append(`
                            <div class="search-result-item">
                                <img src="${imageUrl}" alt="${produk.nama_produk}">
                                <div>
                                    <h5><a href="detail.html?id=${produk.id}">${produk.nama_produk}</h5>
                                    <span class="text-red-500">
                                        ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(produk.harga)}
                                    </span>
                                </div>
                            </div>`);

                        });
                        $searchResult.show(); // Tampilkan hasil pencarian jika ada produk
                    } else {
                        $searchResult.append('<p class="p-2 text-sm text-gray-500">Produk tidak ditemukan.</p>').show();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("Gagal mengambil data produk:", textStatus, errorThrown);
                }
            });
        }, 300); // Delay selama 300ms setelah pengguna berhenti mengetik
    });

    // Fungsi untuk memuat semua produk saat halaman pertama kali dimuat
    function loadAllProducts() {
        clearSearchResults(); // Kosongkan hasil pencarian saat halaman dimuat
    }

    // Panggil fungsi untuk memuat semua produk dan reset tampilan pencarian
    loadAllProducts();
});
