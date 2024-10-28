<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class orderController extends Controller
{
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function postorder(Request $request){
        $validate = $request->validate([
            'cart_id' => 'required|array', 
            'cart_id.*' => 'required|exists:cart,id',  
        ]);

        $user_id = Auth::user()->id;
        $totalHarga = 0;
        $totalBarang = 0;

        foreach ($request->cart_id as $cartId) {
            $cart = Cart::findOrFail($cartId);

            $totalHarga += $cart->total_harga;  
            $totalBarang += $cart->total_barang;

            $notagihan = $this->generateRandomString();
    
            Order::create([
                'cart_id' => $cart->id,
                'user_id' => $user_id,
                'notagihan' => $notagihan,
                'total_barang' => $total_barang,
                'total_harga' => $total_harga,
            ]);
        }
    
        return response()->json([
            'message' => 'Order berhasil dibuat',
            'no_tagihan' => $notagihan,
            'total_barang' => $total_barang,
            'total_harga' => $total_harga,
        ]);
    }

    public function doneorder(){
        
    }
}
