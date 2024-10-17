<?php

namespace App\Http\Controllers;

use App\Http\Resources\userDetailRecourse;
use App\Http\Resources\userResource;
use App\Models\User;
use Illuminate\Http\Request;


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
}
