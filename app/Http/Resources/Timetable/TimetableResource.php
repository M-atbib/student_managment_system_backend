<?php

namespace App\Http\Resources\Timetable;

use App\Http\Resources\Group\GroupResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimetableResource extends JsonResource
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
            'name_file' => $this->name_file,
            'group' => GroupResource::make($this->whenLoaded('group'))
        ];
    }
}
