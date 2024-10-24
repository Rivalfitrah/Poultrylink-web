<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\produkResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\supplierResource;

class supplierController extends Controller
{
    //get all supplier
    public function supplierindex(){
        $supplier = Supplier::all();
        return supplierResource::collection($supplier);
    }

    //getproduk
    public function getproduk(){
        $produk = Produk::all();
        return produkResource::collection($produk->loadMissing('getSupplier'));
    }

    //post produk
    public function postproduk(Request $request) {

        $validate = $request->validate([
            'nama_produk' =>'required',
            'deskripsi' =>'required',
            'harga' => 'required',
            'kategori_id' =>'required|exists:kategori,id',
            'image' => 'nullable|file', // Validasi image agar nullable dan berbentuk file
        ]);

        $image = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('produk', 'public'); 
        }

        $request['image'] = $image ? $image : null;
        $produk = Produk::create($request->all());
        return new produkResource($produk->loadMissing('getSupplier'));;

    }

    //update produk
    public function updateProduk(Request $request, $id) {

        $validate = $request->validate([
            'nama_produk' =>'nullable',
            'deskripsi' =>'nullable',
            'harga' =>'nullable',
            'kategori_id' =>'nullable|exists:kategori,id',
            'image' => 'nullable|file', // Validasi image agar nullable dan berbentuk file
        ]);

        Log::info($request->all());

        // Cari produk berdasarkan ID
        $produk = Produk::findOrFail($id);
    
        $image = $produk->image; // Gunakan gambar lama jika tidak diubah
    
        if ($request->hasFile('image')) {
            if ($image) {
                Storage::delete('public/produk/' . $image);
            }
    
            $fileName = $this->generateRandomString();
            $extension = $request->file('image')->extension();
            $image = $fileName . '.' . $extension;
    
            Storage::putFileAs('public/produk', $request->file('image'), $image);
        }
    
        $dataToUpdate = [];
    
        if ($request->has('nama_produk') && $request->input('nama_produk') != $produk->nama_produk) {
            $dataToUpdate['nama_produk'] = $request->input('nama_produk');
        }
        if ($request->has('deskripsi') && $request->input('deskripsi') != $produk->deskripsi) {
            $dataToUpdate['deskripsi'] = $request->input('deskripsi');
        }
        if ($request->has('harga') && $request->input('harga') != $produk->kategori_id) {
            $dataToUpdate['harga'] = $request->input('harga');
        }
        if ($request->has('kategori_id') && $request->input('kategori_id') != $produk->kategori_id) {
            $dataToUpdate['kategori_id'] = $request->input('kategori_id');
        }
        if ($request->hasFile('image')) {
            $dataToUpdate['image'] = $image;
        }
    
        if (!empty($dataToUpdate)) {
            $produk->update($dataToUpdate);
            $updatedFields = $produk->only(array_keys($dataToUpdate));
            return response()->json([
                'message' => 'Produk berhasil diperbarui',
                'updated' => $updatedFields
            ]);
        } else {
            return response()->json([
                'message' => 'Tidak ada perubahan pada produk',
                'updated' => []
            ]);
        }

        Log::info($dataToUpdate);  // Ini akan menampilkan data yang akan diperbarui
    }
    
    public function deleteproduk($id){
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return response()->json('deleted success');
    }
    
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
    
        return $randomString;
    }

    


    
}
