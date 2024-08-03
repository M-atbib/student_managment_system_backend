<?php

declare(strict_types=1);
namespace App\Responses\etablissement;

use App\Http\Resources\Etablissement\EtablissementCollection;
use App\Http\Resources\Etablissement\EtablissementResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;

class EtablissementResponse implements Responsable{
    public function __construct(
        private readonly Collection $collection,
        private readonly int $status,
    ){}


    public function toResponse($request)
    {
        if ($this->collection->count() === 1) {
            return response()->json(
                data: new EtablissementResource($this->collection->first()),
                status: $this->status,
            );
        }
        return response()->json(
            data:  new EtablissementCollection(resource:$this->collection),
            status: $this->status,
        );
    }
}