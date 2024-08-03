<?php

declare(strict_types=1);
namespace App\Responses\presence;

use App\Http\Resources\Presence\PresenceCollection;
use App\Http\Resources\Presence\PresenceResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;

class PresenceResponse implements Responsable{
    public function __construct(
        private readonly Collection $collection,
        private readonly int $status,
    ){}


    public function toResponse($request)
    {
        if ($this->collection->count() === 1) {
            return response()->json(
                data: new PresenceResource($this->collection->first()),
                status: $this->status,
            );
        }
        return response()->json(
            data:  new PresenceCollection(resource:$this->collection),
            status: $this->status,
        );
    }
}