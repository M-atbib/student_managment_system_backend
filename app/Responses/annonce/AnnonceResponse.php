<?php

declare(strict_types=1);
namespace App\Responses\annonce;

use App\Http\Resources\Annonce\AnnonceCollection;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;

class AnnonceResponse implements Responsable{
    public function __construct(
        private readonly Collection $collection,
        private readonly int $status,
    ){}


    public function toResponse($request)
    {
        return response()->json(
            data:  new AnnonceCollection(resource:$this->collection),
            status: $this->status,
        );
    }
}