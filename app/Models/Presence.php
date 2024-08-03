<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'type',
        'date',
        'justification',
        'remarque',
        'student_uuid'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_uuid', 'uuid');
    }

}
