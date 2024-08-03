<?php

declare(strict_types=1);
namespace App\Responses\timetable;

use App\Http\Resources\Timetable\TimetableCollection;
use App\Http\Resources\Timetable\TimetableResource;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;

class TimetableResponse implements Responsable{
    public function __construct(
        private readonly Collection $collection,
        private readonly int $status,
    ){}


    public function toResponse($request)
    {
        if ($this->collection->count() === 1) {
            return response()->json(
                data: new TimetableResource($this->collection->first()),
                status: $this->status,
            );
        }
        
        return response()->json(
            data: TimetableCollection::make(resource:$this->collection),
            status: $this->status,
        );
    }
}