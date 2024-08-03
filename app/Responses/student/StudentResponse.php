<?php

declare(strict_types=1);
namespace App\Responses\student;

use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\Student\StudentResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class StudentResponse implements Responsable{
    public function __construct(
        private readonly Collection|LengthAwarePaginator $collection,
        private readonly int $status,
    ){}


    public function toResponse($request)
    {
        if ($this->collection->count() === 1) {
            return response()->json(
                data: new StudentResource($this->collection->first()),
                status: $this->status,
            );
        }
        return response()->json(
            data: StudentCollection::make(resource:$this->collection)->response()->getData(),
            status: $this->status,
        );
    }
}