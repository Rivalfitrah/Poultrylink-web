<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Orderdetail;
use Illuminate\Http\Request;
use App\Models\Verifsupplier;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\produkResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\supplierResource;
use App\Http\Resources\kategoriResources;
use App\Http\Controllers\SupabaseStorageService;

class supplierController extends Controller
{
    
    private $supabaseStorageService;
    private $fileController;
    

    public function __construct(SupabaseStorageService $supabaseStorageService, fileController $fileController)
    {
        $this->supabaseStorageService = $supabaseStorageService;
    }


    //get all supplier
    public function supplierindex()
    {
        $supplier = Supplier::all();
        return supplierResource::collection($supplier);
    }

    //getproduk
    public function getproduk()
    {
        $produk = Produk::all();
        return produkResource::collection($produk);
    }

    public function kategori(){
        $kategori = Kategori::all();
        return kategoriResources::collection($kategori);
    }

    public function postinfo(Request $request)
    {
        $validate = $request->validate([
            'nama_toko' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'kodepos' => 'required',
            'provinsi' => 'required',
            'negara' => 'required',
            'deskripsi' => 'required',
        ]);
        
        $request['user_id'] = Auth::user()->id;
        $supplierData = $request->all();

        $supplier = Supplier::create($supplierData);
        return new supplierResource($supplier->loadMissing('userGet'));
    }

    //post produk
    public function postproduk(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Authorization");

        // Ambil user ID dari token yang terautentikasi
        $user = $request->user();
        
        // Cari supplier berdasarkan user_id
        $supplier = Supplier::where('user_id', $user->id)->first();

        if (!$supplier) {
            return response()->json(['error' => 'User is not registered as a supplier'], 403);
        }

        $validate = $request->validate([
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'kategori_id' => 'required|exists:kategori,id',
            'supplier_id' => 'nullable|exists:supplier,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file',
        ]);

        try{
            // Create product without images
            $produkData = $request->except('images');
            $produkData['supplier_id'] = $supplier->id;

            $produk = Produk::create($produkData);

            // Set folder name to product ID
            $filePathSupabase = "{$produk->id}";
            $images = [];

            if ($request->hasFile('images')) {
                $files = $request->file('images');
                if (is_array($files)) {
                    // Upload multiple files
                    $results = $this->supabaseStorageService->uploadMultipleFiles($files, $filePathSupabase);
                    foreach ($results as $result) {
                        if (isset($result['data']['url'])) {
                            $images[] = $result['data']['url']; // Optionally, store individual URLs if needed
                        }
                    }
                } else {
                    // Upload single file
                    $result = $this->supabaseStorageService->uploadFile($files->getRealPath(), $filePathSupabase);
                    if (isset($result['url'])) {
                        $images[] = $result['url']; // Optionally, store the URL
                    }
                }
            }

            // Store only the product ID as the image path in the database
            $produk->update(['image' => $filePathSupabase]);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'produk' => $produk, ]);
                
        } catch (\Exception $e) {
            Log::error('Error during product creation: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create product'], 500);
        }
    }

    //update
    public function updateproduk(Request $request, $id)
    {
        $validate = $request->validate([
            'nama_produk' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'harga' => 'nullable|numeric',
            'kategori_id' => 'nullable|exists:kategori,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file',
        ]);

        // Temukan produk berdasarkan ID
        $produk = Produk::findOrFail($id);

        // Update atribut non-gambar
        $produk->fill($request->except('images'));
        $produk->save();

        // Path folder di Supabase berdasarkan ID produk
        $filePathSupabase = "{$produk->id}";

        // Pastikan kolom image diambil sebagai array atau inisialisasi sebagai array kosong
        $images = json_decode($produk->image, true);
        if (!is_array($images)) {
            $images = []; // Pastikan images selalu berupa array
        }

        // Tambahkan gambar baru jika ada
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            foreach ($files as $key => $file) {
                if ($file) {
                    // Tentukan nama file dalam folder produk yang tepat
                    $fileName = ($key + 1) . ".jpg"; // Misalnya: 1.jpg, 2.jpg, dll.
                    $filePath = "{$filePathSupabase}";  // Path lengkap untuk Supabase

                    // Hapus file lama di storage jika ada di indeks yang sama
                    if (isset($images[$key])) {
                        if ($this->supabaseStorageService->fileExists($filePathSupabase. '/' .basename($images[$key]))) {
                            $this->supabaseStorageService->deleteFile($filePathSupabase. '/' .basename($images[$key]));
                        } 
                    }

                    // Upload file baru dan simpan URL di array $images pada posisi yang sama
                    $result = $this->supabaseStorageService->uploadFile($file->getRealPath(), $filePath);
                    
                    if (isset($result['url'])) {
                        // Simpan URL gambar yang baru
                        $images[$key] = $result['url']; // Simpan URL yang baru
                    }
                }
            }
        }

        // Update kolom image dengan array JSON terbaru
        $produk->update(['image' => $filePathSupabase]);

        return new produkResource($produk->loadMissing('getSupplier'));
    }
    

    public function deleteproduk($id){
        // Temukan produk berdasarkan ID
        $produk = Produk::findOrFail($id);
        $filePathSupabase = "{$produk->id}";

        if ($produk->image) {
            $this->supabaseStorageService->deleteFile($filePathSupabase);
        }
        $produk->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus',
            'id' => $id
        ], 200);
    }

    public function getorder(Request $request)
    {
        $user = $request->user();
        
        // Cari supplier berdasarkan user_id
        $supplier = Supplier::where('user_id', $user->id)->first();

        if (!$supplier) {
            return response()->json(['error' => 'User is not registered as a supplier'], 403);
        }
    
        // Ambil semua order yang terkait dengan supplier_id produk di order_detail
        $orders = Orderdetail::where('supplier_id', $supplier->id) // Filter berdasarkan supplier_id
                            ->join('order', 'order_details.order_id', '=', 'order.id') // Gabungkan dengan tabel orders
                            ->select('order.id as order_id', 'order.user_id', 'order.total', 'order.harga', 'order.confirmed', 'order_details.produk_id', 'order_details.quantity', 'order_details.price', 'order_details.total_price')
                            ->get();
    
        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada pesanan untuk supplier ini.'
            ], 404);
        }
    
        return response()->json([
            'message' => 'Data pesanan berhasil diambil.',
            'orders' => $orders
        ]);
    }

    public function getProdukSupplier(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        $user = $request->user();
        $supplier = Supplier::where('user_id', $user->id)->first();
    
        if (!$supplier) {
            return response()->json(['error' => 'User is not registered as a supplier'], 403);
        }
    
        $produks = Produk::where('supplier_id', $supplier->id)->get();
        return response()->json(['success' => true, 'produks' => $produks]);
    }

    public function checkSupplier(Request $request)
    {
        $user = Auth::user();
        $supplier = Supplier::where('user_id', $user->id)->first();
        return response()->json([
            'id_supplier' => $supplier ? $supplier->id : null,
        ]);
    }
}
