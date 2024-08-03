<?php

namespace App\Http\Resources\Group;

use App\Http\Resources\Group\GroupResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GroupCollection extends ResourceCollection
{
    public $collects = GroupResource::class;

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
