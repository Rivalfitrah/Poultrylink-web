<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Models\Verifsupplier;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SupabaseStorageVerif;

class verifController extends Controller
{
    private $storageService;

    public function __construct(SupabaseStorageVerif $storageService)
    {
        $this->storageService = $storageService;
    }

    public function postverif(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User is not authenticated'], 401);
        }
    
        $buyerExists = Buyer::where('user_id', $user->id)->exists();
        if (!$buyerExists) {
            return response()->json(['error' => 'User is not registered as a buyer'], 400);
        }
    
        $validate = $request->validate([
            'nik' => 'required|string',
            'nama' => 'required|string',
            'ttl' => 'required|string',
            'alamat' => 'required|string',
            'kewarganegaraan' => 'required|string',
            'images' => 'required|array',
            'images.*' => 'required|file',
        ]);

         // otomatis detect siapa 
        $verif = Verifsupplier::create(array_merge(
            $request->except('images'),
            ['user_id' => $user->id] // Set user_id dari user yang login
        ));
    
        // Atur folder di Supabase berdasarkan user ID
        $filePathSupabase = "{$user->id}";
        $images = [];

        if ($request->hasFile('images')) {
            $files = $request->file('images');
            $results = $this->storageService->uploadMultipleFiles($files, $filePathSupabase);
            
            foreach ($results as $result) {
                if (isset($result['data']['url'])) {
                    $images[] = $result['data']['url'];
                }
            }
        }
        $verif->update(['image' => $filePathSupabase]);
    
        return response()->json([
            'message' => 'Verification and file upload successful',
            'verif' => $verif,
            'uploaded_images' => $images,
        ]);
    }
}
