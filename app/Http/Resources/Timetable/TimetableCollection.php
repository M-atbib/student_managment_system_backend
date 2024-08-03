<?php

namespace App\Http\Resources\Timetable;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TimetableCollection extends ResourceCollection
{
    public $collects = TimetableResource::class;

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
