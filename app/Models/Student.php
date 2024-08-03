<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    protected $guard_name = 'web';

    protected $table = 'students';

    protected $fillable = [
        'uuid',
        'inscription_number',
        'CIN',
        'id_massar',
        'full_name',
        'birth_date',
        'birth_place',
        'gender',
        'school_level',
        'phone_number',
        'address',
        'email',
        'password',
        'plain_password',
        'responsable',
        'photo',
        'training_duration',
        'sector',
        'filières_formation',
        'training_level',
        'group_uuid',
        'monthly_amount',
        'registration_fee',
        'product',
        'frais_diplôme',
        'annual_amount',
        'status',
        'date_start_at',
        'date_fin_at'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'student_uuid', 'uuid');
    }

    public function remarques(): HasMany
    {
        return $this->hasMany(Remarque::class, 'student_uuid', 'uuid');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'student_uuid', 'uuid');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class,'student_uuid', 'uuid');
    }
    
    public function groups()
    {
        return $this->hasMany(Group::class,'uuid', 'group_uuid');
    }
    
}
