<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\buyerResource;
use App\Http\Resources\ulasanResource;

class buyerController extends Controller
{
    //get user by id
    public function buyerIndex($id){
        $buyer = Buyer::with('userGet')->findOrFail($id);
        return new buyerResource($buyer->loadMissing(['ulasanGet', 'userGet']));
    }

    //masukin data pertama kali bukan update
    public function postprofile(Request $request){
        
        $validate = $request->validate([
            'firstname' => 'required',
            'lastname' => 'nullable',
            'alamat' => 'required',
            'telepon' => 'required|unique:buyer',  // Validasi di tabel buyer
            'kota' => 'required',
            'kodepos' => 'required',
            'provinsi' => 'required',
            'negara' => 'required',
            'image' => 'nullable|file|max:2048',  // Validasi untuk gambar
        ]);

        // Upload gambar jika ada
        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('buyer', 'public'); // Simpan di folder 'buyer'
        }

        $request['user_id'] = Auth::user()->id;
        $buyerData = $request->all();
        
        if ($image) {
            $buyerData['image'] = $image; 
        }

        $buyer = Buyer::create($buyerData);
        return new buyerResource($buyer->loadMissing('userGet'));
    }

    //update profile aja 
    public function updateprofile(Request $request, $id) {
        // Validasi data input
        $validate = $request->validate([
            'firstname' => 'nullable',
            'lastname' => 'nullable',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
            'kota' => 'nullable',
            'kodepos' => 'nullable',
            'provinsi' => 'nullable',
            'negara' => 'nullable',
            'image' => 'nullable|file|max:2048',  // Validasi untuk gambar
        ]);

        $buyer = Buyer::findOrFail($id);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($buyer->image) {
                Storage::delete('public/' . $buyer->image);  // Hapus gambar lama dari storage
            }

            // Simpan gambar baru
            $image = $request->file('image')->store('buyer', 'public');
            $buyer->image = $image; // Update path gambar di database
        }

        $buyer->update(array_filter($request->all()));

        return new buyerResource($buyer->loadMissing('userGet'));
    }

    //post ulasan
    public function ulasan (Request $request){
        $validate = $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'ulasan' => 'required', 
        ]);

        $request['user_id'] = auth()->user()->id; 
        $ulasan = Ulasan::create($request->all());
        return new ulasanResource($ulasan->loadMissing('userGet'));
    }

    //update ulasan
    public function updateUlasan(Request $request, $id){
        $validate = $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'ulasan' => 'required', 
        ]);

        $ulasan = Ulasan::findOrFail($id); 
        $ulasan->update($request->all());
        return new ulasanResource($ulasan->loadMissing('userGet'));
    }

    //delete ulasan
    public function deleteUlasan($id){
        $ulasan = Ulasan::findOrFail($id);
        $ulasan->delete();
        return response()->json('deleted success');
    }
    

}
