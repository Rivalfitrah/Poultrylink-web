<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class produkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'nama_produk' => $this->nama_produk,
            'deskripsi' => $this->deskripsi,
            'harga' => $this->harga,
            'rating' => $this->rating,
            'kategori_id' => $this->kategori_id,
            'supplier_id' => $this->supplier_id,
            'image' => $this->image,
            'supplier' => [
                'namatoko' => $this->getSupplier->namatoko
            ]
        ];
    }
}
