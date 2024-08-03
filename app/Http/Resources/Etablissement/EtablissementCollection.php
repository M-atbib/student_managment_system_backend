<?php

namespace App\Http\Resources\Etablissement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EtablissementCollection extends ResourceCollection
{
    public $collects = EtablissementResource::class;

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
