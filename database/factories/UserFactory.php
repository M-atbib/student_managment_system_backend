<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Etablissement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'uuid' => (string) Str::uuid(),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'branch_uuid' => Etablissement::inRandomOrder()->first()->uuid,
            'password' => Hash::make('password'), // Mot de passe par défaut
            'remember_token' => Str::random(10),
        ];
    }
}
