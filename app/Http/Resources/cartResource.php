<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class cartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total_harga' => $this->total_harga,
            'total_barang' => $this->total_barang,
            'produk_id' => $this->produk_id,
            'barang' => [
                'nama_produk' => $this->produkGet->nama_produk,
                'harga' => $this->produkGet->harga,
            ],
            'user' => [
                'username' => $this->userGet->username,
            ],
        ];
    }
}
