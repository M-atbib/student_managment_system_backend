<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentNumInscription extends Model
{
    use HasFactory;

    protected $table = 'student__num_inscriptions';

    protected $fillable = [
        'inscription_num',
        'etab_uuid',
    ];
}
