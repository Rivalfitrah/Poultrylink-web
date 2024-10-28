<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Http\Resources\cartResource;
use Illuminate\Support\Facades\Auth;

class cartController extends Controller
{
    public function indexcart(){
        $cart = Cart::all();
        return cartResource::collection($cart);
    }

    public function postcart(Request $request){
        $validate = $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'total_barang' => 'required|integer|min:1',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $total_harga = $produk->harga * $request->total_barang;

        $cart = Cart::create([
            'user_id' => Auth::id(),
            'produk_id' => $request->produk_id,
            'total_barang' => $request->total_barang,
            'total_harga' => $total_harga, // Simpan total harga yang sudah dihitung
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart' => $cart
        ]);
    }

    public function updatecart(Request $request, $id){
        $validate = $request->validate([
            'total_barang' => 'required|integer|min:1', 
        ]); 

        $cart = Cart::findOrFail($id); 
        $produk = Produk::findOrFail($cart->produk_id);
        $total_harga = $produk->harga * $request->total_barang;


        $cart->update([
            'total_barang' => $request->total_barang,
            'total_harga' => $total_harga,
        ]);

        return new CartResource($cart->loadMissing(['produkGet','userGet']));
    }

    public function postorder(Request $request){
        $validate = $request->validate([
            'cart_id' => 'required|array', 
            'cart_id.*' => 'required|exists:cart,id',  
        ]);
    
        $user_id = Auth::user()->id;
        $orders = [];
    
        foreach ($request->cart_id as $cartId) {
            $cart = Cart::findOrFail($cartId);
    
            $totalHarga = $cart->total_harga;
            $totalBarang = $cart->total_barang;
    
            $notagihan = $this->generateRandomString();
    
            $order = Order::create([
                'cart_id' => $cart->id,
                'user_id' => $user_id,
                'notagihan' => $notagihan,
                'total' => $totalBarang,
                'harga' => $totalHarga,
            ]);
    
            $orders[] = [
                'order_id' => $order->id,
                'cart_id' => $cart->id,
                'notagihan' => $notagihan,
                'total' => $totalBarang,
                'harga' => $totalHarga,
            ];
        }
    
        return response()->json([
            'message' => 'Order berhasil dibuat',
            'orders' => $orders,
        ]);
    }
    

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function deleteitem(){
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return response()->json('deleted success');
    }

}
