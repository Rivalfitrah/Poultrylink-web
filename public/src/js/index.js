$(document).ready(function () {
    var $produk = $("#produk");
    if ($produk.length > 0) {
        console.log("Element #produk ditemukan. Mulai mengambil data produk dari API Supabase...");
        $.ajax({
            url: "https://hbssyluucrwsbfzspyfp.supabase.co/rest/v1/produk?limit=20",
            type: "GET",
            headers: {
                "apikey": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imhic3N5bHV1Y3J3c2JmenNweWZwIiwicm9sZSI6ImFub24iLCJpYXQiOjE3Mjk2NTU4OTEsImV4cCI6MjA0NTIzMTg5MX0.o6fkro2tPKFoA9sxAp1nuseiHRGiDHs_HI4-ZoqOTfQ",
                "Authorization": "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imhic3N5bHV1Y3J3c2JmenNweWZwIiwicm9sZSI6ImFub24iLCJpYXQiOjE3Mjk2NTU4OTEsImV4cCI6MjA0NTIzMTg5MX0.o6fkro2tPKFoA9sxAp1nuseiHRGiDHs_HI4-ZoqOTfQ"
            },
            success: function (produks) {
                console.log("Data produk berhasil diambil:", produks);

                $.each(produks, function (i, produk) {
                    const imageUrl = produk.image
                        ? `https://hbssyluucrwsbfzspyfp.supabase.co/storage/v1/object/public/products/${produk.image}/1.jpg;`
                        : './path/to/default-image.jpg';

                    $produk.append(
                        `<div class="relative m-3 w-full max-w-[14rem] overflow-hidden rounded-lg bg-white shadow-md" data-id="${produk.id}" data-aos="flip-left" data-aos-duration="2000">
                            <a href="detail.html?id=${produk.id}" class="group">
                                <img 
                                class="h-40 w-full rounded-t-lg object-cover transition-transform duration-300 ease-in-out group-hover:scale-110" 
                                src="${imageUrl}" 
                                alt="${produk.nama_produk}" 
                                />
                            </a>
                            <span class="absolute top-0 left-0 w-16 translate-y-3 -translate-x-4 -rotate-45 bg-black text-center text-xs text-white">
                                Sale
                            </span>
                            <div class="mt-3 px-4 pb-4">
                                <a href="detail.html?id=${produk.id}">
                                <h5 class="text-base font-semibold tracking-tight text-slate-900">
                                    ${produk.nama_produk}
                                </h5>
                                </a>
                                <div class="mt-2.5 mb-4 flex items-center">
                                    <span class="mr-1 rounded bg-yellow-200 px-2 py-0.5 text-xs font-semibold">5.0</span>
                                    <svg aria-hidden="true" class="h-4 w-4 text-yellow-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p>
                                        <span class="text-base font-bold text-slate-900">Rp.${produk.harga}</span>
                                    </p>
                                    <a href="./shop.html"
                                        class="flex items-center rounded-md bg-primary px-3 py-1.5 text-center text-xs font-medium text-white hover:bg-white hover:text-primary hover:border hover:border-primary focus:outline-none focus:ring-4 focus:ring-blue-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>`
                    );
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Gagal mengambil data produk:", textStatus, errorThrown);
            },
        });
    } else {
        console.log("Element #produk tidak ditemukan di halaman.");
    }
});


    
       
    

