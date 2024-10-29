<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\userResource;
use App\Http\Resources\userDetailRecourse;


class userController extends Controller
{
    //return semua data
    public function index () {
        $users = User::all();
        return userResource::collection($users);
    }

    //get satu data
    public function show ($id){
        $user = User::findOrFail($id);
        return new userDetailRecourse ($user);
    }

    //create account
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
    
        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'something wrong',
                'data' => $validator->errors(),
            ]);
        }
    
        // Mengambil data tanpa `confirm_password`
        $input = $request->except('confirm_password');
        $input['password'] = bcrypt($input['password']); // Enkripsi password
    
        $user = User::create($input);
    
        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['username'] = $user->username;
    
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $success,
        ]);
    }
    
}
