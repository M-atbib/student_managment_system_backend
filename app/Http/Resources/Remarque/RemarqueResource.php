<?php

namespace App\Http\Resources\Remarque;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RemarqueResource extends JsonResource
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
            'updated_at' => $this->updated_at,
        ];
    }
}
