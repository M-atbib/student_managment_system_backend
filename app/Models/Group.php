<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $fillable = [
        'uuid',
        'name',
        'etab_uuid'
    ];

    public function timetableinfo(): BelongsTo
    {
        return $this->belongsTo(Timetable::class, 'group_uuid', 'uuid');
    }
    public function countStudents(){
        return Student::where('group_uuid',$this->uuid)->where('status','active')->count();
    }
}
