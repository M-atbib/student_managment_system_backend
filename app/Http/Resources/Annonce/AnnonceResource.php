<?php

namespace App\Http\Resources\Annonce;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnonceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'text' => $this->text,
            'date_validite' => $this->date_validite,
            'sector' => $this->sector,
            'etab_uuid' => $this->etab_uuid,
            'created_at' => $this->created_at,

        ];
    }
}
