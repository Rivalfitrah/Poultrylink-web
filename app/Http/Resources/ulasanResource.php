<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ulasanResource extends JsonResource
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
            'produk_id' => $this->produk_id,
            'user_id' => $this->user_id,
            'ulasan' => $this->ulasan,
            'created_at' => date_format($this->created_at, "Y/m/d H:i:s"),
            // Informasi tambahan dari relasi user
            'user' => [
                'id' => $this->userGet->id,
                'username' => $this->userGet->username,
            ],
        ];
    }
}
