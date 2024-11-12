<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Produk;
use App\Models\Orderdetail;
use Illuminate\Http\Request;
use App\Http\Resources\cartResource;
use Illuminate\Support\Facades\Auth;

class cartController extends Controller
{
    public function indexcart(){
        $cart = Cart::all();
        return cartResource::collection($cart);
    }

    public function postcart(Request $request)
    {
        // Validasi input
        $validate = $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'total_barang' => 'required|integer|min:1',
        ]);

        // Ambil produk berdasarkan ID
        $produk = Produk::findOrFail($request->produk_id);
        $total_harga = $produk->harga * $request->total_barang;

        // Cek apakah produk sudah ada di cart
        $existingCart = Cart::where('user_id', Auth::id())
                            ->where('produk_id', $request->produk_id)
                            ->first();

        // Jika produk sudah ada di cart, update jumlahnya
        if ($existingCart) {
            $existingCart->update([
                'total_barang' => $existingCart->total_barang + $request->total_barang, // Menambahkan jumlah yang dipesan
                'total_harga' => $existingCart->total_harga + $total_harga, // Menambahkan harga total
            ]);
            return response()->json([
                'message' => 'Produk sudah ada di keranjang, jumlah diperbarui',
                'cart' => $existingCart
            ]);
        }

        // Jika produk belum ada di cart, buat entri baru
        $cart = Cart::create([
            'user_id' => Auth::id(),
            'produk_id' => $request->produk_id,
            'total_barang' => $request->total_barang,
            'total_harga' => $total_harga,
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart' => $cart
        ]);
    }

    public function updatecart(Request $request, $id)
    {
        // Validasi input
        $validate = $request->validate([
            'total_barang' => 'required|integer|min:1',
        ]);

        // Ambil cart berdasarkan ID
        $cart = Cart::findOrFail($id);

        // Pastikan cart milik user yang sedang login
        if ($cart->user_id !== Auth::id()) {
            return response()->json(['error' => 'Anda tidak memiliki akses ke cart ini'], 403);
        }

        // Ambil produk terkait dan hitung harga total baru
        $produk = Produk::findOrFail($cart->produk_id);
        $total_harga = $produk->harga * $request->total_barang;

        // Update cart
        $cart->update([
            'total_barang' => $request->total_barang,
            'total_harga' => $total_harga,
        ]);

        return response()->json([
            'message' => 'Cart berhasil diperbarui',
            'cart' => $cart
        ]);
    }

    public function postorder(Request $request)
    {
        // Validasi input
        $request->validate([
            'cart_id' => 'required|array',
            'cart_id.*' => 'exists:cart,id',
        ]);
    
        // Buat order baru
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => 0,  // Total barang
            'harga' => 0,  // Total harga
            'confirmed' => 'no',  
        ]);
    
        $total_harga = 0;
        $total_barang = 0;
    
        foreach ($request->cart_id as $cart_id) {
            $cart = Cart::findOrFail($cart_id);
            $produk = Produk::findOrFail($cart->produk_id);
    
            // Harga per barang
            $harga_per_barang = $produk->harga;
    
            // Total harga per produk (harga per barang * kuantitas)
            $total_harga_item = $harga_per_barang * $cart->total_barang;
    
            // Ambil supplier_id dari produk
            $supplier_id = $produk->supplier_id;
    
            // Simpan ke order_details
            Orderdetail::create([
                'order_id' => $order->id,
                'produk_id' => $produk->id,
                'cart_id' => $cart->id,
                'quantity' => $cart->total_barang,
                'price' => $harga_per_barang,  // Menyimpan harga per barang
                'total_price' => $total_harga_item,  // Menyimpan total harga per item
                'supplier_id' => $supplier_id, // Menyimpan supplier_id
            ]);
    
            $total_harga += $total_harga_item;
            $total_barang += $cart->total_barang;
        }
    
        // Update order total dan harga
        $order->update([
            'total' => $total_barang,  // Total barang
            'harga' => $total_harga,   // Total harga
        ]);
    
        // Hapus cart setelah order dibuat
        Cart::whereIn('id', $request->cart_id)->delete();
    
        return response()->json([
            'message' => 'Order berhasil dibuat',
            'order' => $order
        ]);
    }
    
    
    
    
    
    // public function postorder(Request $request){
    //     $validate = $request->validate([
    //         'cart_id' => 'required|array', 
    //         'cart_id.*' => 'required|exists:cart,id',  
    //     ]);
    
    //     $user_id = Auth::user()->id;
    //     $orders = [];
    
    //     foreach ($request->cart_id as $cartId) {
    //         $cart = Cart::findOrFail($cartId);
    
    //         $totalHarga = $cart->total_harga;
    //         $totalBarang = $cart->total_barang;
    
    //         $notagihan = $this->generateRandomString();
    
    //         $order = Order::create([
    //             'cart_id' => $cart->id,
    //             'user_id' => $user_id,
    //             'notagihan' => $notagihan,
    //             'total' => $totalBarang,
    //             'harga' => $totalHarga,
    //         ]);
    
    //         $orders[] = [
    //             'order_id' => $order->id,
    //             'cart_id' => $cart->id,
    //             'notagihan' => $notagihan,
    //             'total' => $totalBarang,
    //             'harga' => $totalHarga,
    //         ];
    //     }
    
    //     return response()->json([
    //         'message' => 'Order berhasil dibuat',
    //         'orders' => $orders,
    //     ]);
    // }
    
    // function generateRandomString($length = 10) {
    //     $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //     $charactersLength = strlen($characters);
    //     $randomString = '';
    //     for ($i = 0; $i < $length; $i++) {
    //         $randomString .= $characters[random_int(0, $charactersLength - 1)];
    //     }
    //     return $randomString;
    // }

    public function deleteitem($id){
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return response()->json('deleted success');
    }

}
