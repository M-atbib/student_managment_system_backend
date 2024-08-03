<?php

namespace App\Http\Resources\Student;

use App\Http\Resources\Student\StudentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StudentCollection extends ResourceCollection
{
    public $collects = StudentResource::class;

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
