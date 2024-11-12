<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Facade;
use Illuminate\Validation\ValidationException;

class authenticationController extends Controller
{
    public function login (Request $request){
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
    
            $token = $user->createToken('buyer_token')->plainTextToken;
    
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil sebagai buyer',
                'data' => [
                    'token' => $token
                ]
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ]);
    }

    public function loginSupplier (Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            // Cek apakah user tersebut terdaftar sebagai supplier
            $isSupplier = Supplier::where('user_id', $user->id)->exists();

            if ($isSupplier) {
                // Buat token baru untuk user yang terdaftar sebagai supplier
                $token = $user->createToken('supplier_token')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'data' => [
                        'token' => $token,
                        'username' => $user->username,
                        'email' => $user->email,
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Anda bukan supplier.',
                    'data' => null,
                ], 403);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Cek kembali kredensial Anda',
                'data' => null,
            ], 401);
        }
    }

    
    public function logout (Request $request){
        $request->user()->currentAccessToken()->delete();
    }

    public function detectUser (Request $request){
        return response()->json(Auth::user());
    }

    
}
