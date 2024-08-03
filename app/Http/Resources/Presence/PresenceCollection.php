<?php

namespace App\Http\Resources\Presence;

use App\Http\Resources\Presence\PresenceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PresenceCollection extends ResourceCollection
{
    public $collects = PresenceResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection;
    }
}
