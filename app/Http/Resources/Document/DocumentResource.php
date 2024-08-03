<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
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
            'name_file' => $this->name_file,
            'updated_at' => $this->updated_at,
        ];
    }
}
