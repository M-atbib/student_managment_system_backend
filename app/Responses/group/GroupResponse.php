<?php

declare(strict_types=1);
namespace App\Responses\group;

use App\Http\Resources\Group\GroupCollection;
use App\Http\Resources\Group\GroupResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;

class GroupResponse implements Responsable{
    public function __construct(
        private readonly Collection $collection,
        private readonly int $status,
    ){}


    public function toResponse($request)
    {
        if ($this->collection->count() === 1) {
            return response()->json(
                data: new GroupResource($this->collection->first()),
                status: $this->status,
            );
        }
        return response()->json(
            data:  new GroupCollection(resource:$this->collection),
            status: $this->status,
        );
    }
}