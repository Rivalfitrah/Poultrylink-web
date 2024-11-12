<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\buyerResource;
use App\Http\Resources\ulasanResource;
use App\Http\Controllers\SupabaseStorageAvatar;

class buyerController extends Controller
{
    //get user by id
    public function buyerIndex($id){
        $buyer = Buyer::with('userGet')->findOrFail($id);
        return new buyerResource($buyer->loadMissing(['ulasanGet', 'userGet']));
    }

    protected $supabaseStorage;

    public function __construct(SupabaseStorageAvatar $supabaseStorage)
    {
        $this->supabaseStorage = $supabaseStorage;
    }


    //masukin data pertama kali bukan update
    public function postprofile(Request $request)
    {
        $validate = $request->validate([
            'firstname' => 'required',
            'lastname' => 'nullable',
            'alamat' => 'required',
            'telepon' => 'required|unique:buyer',
            'kota' => 'required',
            'kodepos' => 'required',
            'provinsi' => 'required',
            'negara' => 'required',
            'avatar_path' => 'nullable|file|max:2048',
        ]);

        $request['user_id'] = Auth::user()->id;
        $buyerData = $request->all();
        $buyer = Buyer::create($buyerData);

        if ($request->hasFile('avatar_path')) {
            $file = $request->file('avatar_path');
            $filePath = $file->getPathname();
            $filePathInSupabase = $buyer->id;

            $uploadResult = $this->supabaseStorage->uploadFile($filePath, $filePathInSupabase);
            if (isset($uploadResult['url'])) {
                $buyer->avatar_path = $uploadResult['url'];
                $buyer->save();
            }
        }
        return new buyerResource($buyer->loadMissing('userGet'));
    }

    //update profile aja 
    public function updateprofile(Request $request)
    {
        $request->validate([
            'firstname' => 'nullable|string',
            'lastname' => 'nullable|string',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|unique:buyer,telepon,' . Auth::user()->id,
            'kota' => 'nullable|string',
            'kodepos' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'negara' => 'nullable|string',
            'avatar_path' => 'nullable|file|max:2048', // Validasi untuk gambar
        ]);

        $buyer = Buyer::where('user_id', Auth::user()->id)->first();

        if (!$buyer) {
            return response()->json(['error' => 'Buyer not found'], 404);
        }

        $buyer->firstname = $request->input('firstname', $buyer->firstname);
        $buyer->lastname = $request->input('lastname', $buyer->lastname);
        $buyer->alamat = $request->input('alamat', $buyer->alamat);
        $buyer->telepon = $request->input('telepon', $buyer->telepon);
        $buyer->kota = $request->input('kota', $buyer->kota);
        $buyer->kodepos = $request->input('kodepos', $buyer->kodepos);
        $buyer->provinsi = $request->input('provinsi', $buyer->provinsi);
        $buyer->negara = $request->input('negara', $buyer->negara);

        // Cek apakah ada gambar baru yang diupload
        if ($request->hasFile('avatar_path')) {
            // Gambar baru ditemukan, hapus gambar lama jika perlu
            $oldFilePath = "{$buyer->id}/1.jpg"; // Path file lama
            if ($this->supabaseStorage->fileExists($oldFilePath)) {
                $this->supabaseStorage->deleteFile($oldFilePath); // hapus file lama jika ada 
            }

            $newFilePathInSupabase = $buyer->id; 
            $uploadResult = $this->supabaseStorage->uploadFile($request->file('avatar_path')->getRealPath(), $newFilePathInSupabase);

            $buyer->avatar_path = $uploadResult['url'];
        }

        $buyer->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
        ], 200);
        
    }

    public function getprofile(Request $request)
    {
        $userId = Auth::id(); // Mengambil user ID yang sedang login
        $buyer = Buyer::where('user_id', $userId)->with('userGet')->first();

        if (!$buyer) {
            return response()->json(['error' => 'Profil tidak ditemukan'], 404);
        }

        return new buyerResource($buyer);
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
