<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class buyerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'alamat' => $this->alamat,
        'telepon' => $this->telepon,
        'kota' => $this->kota,
        'kodepos' => $this->kodepos,
        'provinsi' => $this->provinsi,
        'negara' => $this->negara,
        'user' => [
                'id' => $this->userGet->id,
                'username' => $this->userGet->username,
            ],
        ];
    }
}
