<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        return produkResource::collection($produk->loadMissing('getSupplier'));
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

        //  // Upload gambar jika ada
        //  $image = null;
        //  if ($request->hasFile('image')) {
        //      $image = $request->file('image')->store('supplier', 'public'); // Simpan di folder 'supplier'
        //  }

        $request['user_id'] = Auth::user()->id;
        $supplierData = $request->all();

        //  if ($image) {
        //      $supplierData['image'] = $image;
        //  }

        $supplier = Supplier::create($supplierData);
        return new supplierResource($supplier->loadMissing('userGet'));
    }

    public function updateinfo()
    {
        $validate = $request->validate([
            'nama_toko' => 'nullable',
            'alamat' => 'nullable',
            'kota' => 'nullable',
            'kodepos' => 'nullable',
            'provinsi' => 'nullable',
            'negara' => 'nullable',
            'deskripsi' => 'nullable',
        ]);

        $supplier = Supplier::findOrFail($id);

        // if ($request->hasFile('image')) {
        //     // Hapus gambar lama jika ada
        //     if ($supplier->image) {
        //         Storage::delete('public/' . $supplier->image);  // Hapus gambar lama dari storage
        //     }

        //     // Simpan gambar baru
        //     $image = $request->file('image')->store('supplier', 'public');
        //     $supplier->image = $image; // Update path gambar di database
        // }

        $supplier->update(array_filter($request->all()));
        return new supplierResource($supplier->loadMissing('userGet'));
    }

    //post produk
    public function postproduk(Request $request)
    {

        $validate = $request->validate([
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'kategori_id' => 'required|exists:kategori,id',
            'supplier_id' => 'nullable|exists:supplier,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file',
        ]);

        // Create product without images
        $produk = Produk::create($request->except('images'));

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

        return new produkResource($produk->loadMissing('getSupplier'));
    }

    // public function postproduk(Request $request) {
    //     $validate = $request->validate([
    //         'nama_produk' =>'required',
    //         'deskripsi' =>'required',
    //         'harga' => 'required',
    //         'kategori_id' =>'required|exists:kategori,id',
    //         'image' => 'nullable|file', // Validasi image agar nullable dan berbentuk file
    //     ]);

    //     $image = null;

    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image')->store('produk', 'public');
    //     }

    //     $request['image'] = $image ? $image : null;
    //     $produk = Produk::create($request->all());
    //     return new produkResource($produk->loadMissing('getSupplier'));;

    // }

    //update
    public function updateProduk(Request $request, $id)
    {
        $validate = $request->validate([
            'nama_produk' => 'nullable',
            'deskripsi' => 'nullable',
            'harga' => 'nullable',
            'kategori_id' => 'nullable|exists:kategori,id',
            'image' => 'nullable|file',
        ]);

        // Cari produk berdasarkan ID
        $produk = Produk::findOrFail($id);
        $imageUrl = $produk->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();

            try {
                $supabaseService = new SupabaseStorageService();
                $uploadResult = $supabaseService->uploadFile($file->getRealPath(), "products/{$id}/{$fileName}");
                $imageUrl = $uploadResult['publicURL'] ?? $imageUrl;
            } catch (\Exception $e) {
                return response()->json(['error' => 'File upload to Supabase failed: ' . $e->getMessage()], 500);
            }
        }

        // Array perubahan data yang akan disimpan
        $dataToUpdate = array_filter([
            'nama_produk' => $request->input('nama_produk') !== $produk->nama_produk ? $request->input('nama_produk') : null,
            'deskripsi' => $request->input('deskripsi') !== $produk->deskripsi ? $request->input('deskripsi') : null,
            'harga' => $request->input('harga') !== $produk->harga ? $request->input('harga') : null,
            'kategori_id' => $request->input('kategori_id') !== $produk->kategori_id ? $request->input('kategori_id') : null,
            'image' => $imageUrl,
        ]);

        if (!empty($dataToUpdate)) {
            $produk->update($dataToUpdate);
            return response()->json([
                'message' => 'Produk berhasil diperbarui',
                'updated' => $produk->only(array_keys($dataToUpdate))
            ]);
        } else {
            return response()->json([
                'message' => 'Tidak ada perubahan pada produk',
                'updated' => []
            ]);
        }
    }



    // public function updateProduk(Request $request, $id) {

    //     $validate = $request->validate([
    //         'nama_produk' =>'nullable',
    //         'deskripsi' =>'nullable',
    //         'harga' =>'nullable',
    //         'kategori_id' =>'nullable|exists:kategori,id',
    //         'image' => 'nullable|file', // Validasi image agar nullable dan berbentuk file
    //     ]);

    //     Log::info($request->all());

    //     // Cari produk berdasarkan ID
    //     $produk = Produk::findOrFail($id);

    //     $image = $produk->image; // Gunakan gambar lama jika tidak diubah

    //     if ($request->hasFile('image')) {
    //         if ($image) {
    //             Storage::delete('public/produk/' . $image);
    //         }

    //         $fileName = $this->generateRandomString();
    //         $extension = $request->file('image')->extension();
    //         $image = $fileName . '.' . $extension;

    //         Storage::putFileAs('public/produk', $request->file('image'), $image);
    //     }

    //     $dataToUpdate = [];

    //     if ($request->has('nama_produk') && $request->input('nama_produk') != $produk->nama_produk) {
    //         $dataToUpdate['nama_produk'] = $request->input('nama_produk');
    //     }
    //     if ($request->has('deskripsi') && $request->input('deskripsi') != $produk->deskripsi) {
    //         $dataToUpdate['deskripsi'] = $request->input('deskripsi');
    //     }
    //     if ($request->has('harga') && $request->input('harga') != $produk->kategori_id) {
    //         $dataToUpdate['harga'] = $request->input('harga');
    //     }
    //     if ($request->has('kategori_id') && $request->input('kategori_id') != $produk->kategori_id) {
    //         $dataToUpdate['kategori_id'] = $request->input('kategori_id');
    //     }
    //     if ($request->hasFile('image')) {
    //         $dataToUpdate['image'] = $image;
    //     }

    //     if (!empty($dataToUpdate)) {
    //         $produk->update($dataToUpdate);
    //         $updatedFields = $produk->only(array_keys($dataToUpdate));
    //         return response()->json([
    //             'message' => 'Produk berhasil diperbarui',
    //             'updated' => $updatedFields
    //         ]);
    //     } else {
    //         return response()->json([
    //             'message' => 'Tidak ada perubahan pada produk',
    //             'updated' => []
    //         ]);
    //     }

    //     Log::info($dataToUpdate);  // Ini akan menampilkan data yang akan diperbarui
    // }

    public function deleteproduk($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return response()->json('deleted success');
    }
}
