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
    public function postprofile (Request $request){

        $validate = $request->validate([
            'firstname' => 'required',
            'lastname',
            'alamat' => 'required',
            'telepon' => 'required|unique:buyer',  // Validasi di tabel buyer, bukan users
            'kota' => 'required',
            'kodepos' => 'required',
            'provinsi' => 'required',
            'negara' => 'required',
        ]);

        $request['user_id'] = Auth::user()->id;
        $buyer = Buyer::create($request->all());
        return new buyerResource ($buyer->loadMissing('userGet'));
        // return response()->json('bisa cuy');

    }
    //update profile aja 
    public function updateprofile(Request $request, $id){
        $validate = $request->validate([
            'firstname' => 'required',
            'lastname',
            'alamat' => 'required',
            'telepon' => 'required',  // Validasi di tabel buyer, bukan users
            'kota' => 'required',
            'kodepos' => 'required',
            'provinsi' => 'required',
            'negara' => 'required',
        ]);

        $buyer = Buyer::findOrFail($id);
        $buyer->update($request->all());
        return new buyerResource ($buyer->loadMissing('userGet'));
    }

    //delete ulasan
    public function destroy($id){
        $buyer = Buyer::findOrFail($id);
        $buyer->delete();
        return response()->json('deleted success');
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
