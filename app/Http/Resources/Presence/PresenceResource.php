<?php

namespace App\Http\Resources\Presence;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PresenceResource extends JsonResource
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
            'title' => $this->title,
            'type' => $this->type,
            'date' => $this->date,
            'justification' => $this->justification,
            'remarque' => $this->remarque,
            'student' => $this->student_uuid
        ];
    }
}
