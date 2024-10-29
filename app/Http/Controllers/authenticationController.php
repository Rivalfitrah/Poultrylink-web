<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Facade;
use Illuminate\Validation\ValidationException;

class authenticationController extends Controller
{
    public function login (Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $auth = Auth::user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['username'] = $auth->username;
            $success['email'] = $auth->email;

            return response()->json([
                'success' => true,
                'message' => 'login berhasil',
                'data' => $success
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'cek kembali',
                'data' => null,
            ]);
        }

    }

    public function logout (Request $request){
        $request->user()->currentAccessToken()->delete();
    }

    public function detectUser (Request $request){
        return response()->json(Auth::user());
    }

    
}
