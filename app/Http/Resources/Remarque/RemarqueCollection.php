<?php

namespace App\Http\Resources\Remarque;

use App\Http\Resources\Remarque\RemarqueResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RemarqueCollection extends ResourceCollection
{
    public $collects = RemarqueResource::class;

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
