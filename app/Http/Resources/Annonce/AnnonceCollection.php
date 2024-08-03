<?php

namespace App\Http\Resources\Annonce;

use App\Http\Resources\Annonce\AnnonceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AnnonceCollection extends ResourceCollection
{
    public $collects = AnnonceResource::class;

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
