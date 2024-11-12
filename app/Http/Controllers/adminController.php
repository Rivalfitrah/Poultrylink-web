<?php

namespace App\Http\Controllers;

use App\Models\Atmin;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Verifsupplier;
use App\Mail\SupplierVerifiedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class adminController extends Controller
{
    public function verifysupplier($id)
    {
        // Mencari data verifikasi berdasarkan ID
        $verification = Verifsupplier::find($id);
    
        if (!$verification) {
            return response()->json(['error' => 'Verification data not found'], 404);
        }
    
        // Mengubah status verified menjadi 'yes'
        $verification->verified = 'yes';
        $verification->save();
    
        // Membuat entri baru di tabel supplier dengan user_id
        $user = $verification->user;
        if ($user) {
            $supplier = new Supplier();
            $supplier->user_id = $user->id;
            $supplier->save();
        }
    
        // Kirim email verifikasi
        Mail::to($user->email)->send(new SupplierVerifiedMail($user));
    
        return response()->json(['message' => 'Supplier has been verified, added to suppliers table, and email sent']);
    }

    
    public function login(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Authorization");
    
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        // Cari admin berdasarkan username
        $admin = Atmin::where('username', $request->username)->first();
    
        // Cek apakah admin ditemukan dan password cocok
        if ($admin && $admin->password === $request->password) {
            // Buat token baru
            $token = $admin->createToken('auth_token')->plainTextToken;
    
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'token' => $token,
                    'username' => $admin->username,
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cek kembali kredensial Anda',
                'data' => null,
            ], 401);
        }
    }

}
