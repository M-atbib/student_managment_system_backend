<?php

namespace App\Http\Resources\Etablissement;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EtablissementResource extends JsonResource
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
            'branch_name' => $this->branch_name,
            'branch_logo' => $this->branch_logo,
            'branch_uuid' => $this->uuid,
            'owner' => UserResource::make($this->whenLoaded('user'))
        ];
    }
}
