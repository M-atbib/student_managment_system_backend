<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Etablissement extends Model
{
    use HasFactory;

    use HasFactory;

    protected $table = 'etablissement';

    protected $fillable = [
        'uuid',
        'branch_name',
        'branch_logo'
    ];


    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
