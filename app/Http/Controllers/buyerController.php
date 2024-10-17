<?php

namespace App\Http\Controllers;

use App\Http\Resources\buyerResource;
use App\Models\Buyer;
use Illuminate\Http\Request;

class buyerController extends Controller
{
    public function buyerIndex($id){
        $buyer = Buyer::with('userGet')->findOrFail($id);
        return new buyerResource($buyer);
    }
}
