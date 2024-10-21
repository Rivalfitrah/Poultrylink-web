<?php

namespace App\Http\Controllers;

use App\Http\Resources\userDetailRecourse;
use App\Http\Resources\userResource;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;


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
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'something wrong',
                'data' => $validator->errors(),
            ]);
        }

        $input = $request->all();
        $user = User::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['username'] = $user->username;

        return response()->json([
            'success' => false,
            'message' => 'success',
            'data' => $success,
        ]);

    }
}
