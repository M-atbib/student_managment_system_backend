<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'uuid',
        'student_uuid',
        'type',
        'methode',
        'montant',
        'month',
        'date_payment'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_uuid', 'uuid');
    }
}
