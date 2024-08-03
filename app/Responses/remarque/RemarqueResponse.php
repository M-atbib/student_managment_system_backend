<?php

declare(strict_types=1);
namespace App\Responses\remarque;

use App\Http\Resources\Remarque\RemarqueCollection;
use App\Http\Resources\Remarque\RemarqueResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;

class RemarqueResponse implements Responsable{
    public function __construct(
        private readonly Collection $collection,
        private readonly int $status,
    ){}


    public function toResponse($request)
    {
        if ($this->collection->count() === 1) {
            return response()->json(
                data: new RemarqueResource($this->collection->first()),
                status: $this->status,
            );
        }
        
        return response()->json(
            data: RemarqueCollection::make(resource:$this->collection),
            status: $this->status,
        );
    }
}