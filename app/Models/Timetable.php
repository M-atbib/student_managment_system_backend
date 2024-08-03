<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'group_uuid',
        'name_file'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_uuid', 'uuid');
    }
}
