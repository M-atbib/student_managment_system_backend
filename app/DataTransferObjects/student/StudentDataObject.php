<?php 


declare(strict_types=1);
namespace App\DataTransferObjects\student;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class StudentDataObject
{
    public function __construct(
        private string $inscription_number,
        private ?string $CIN,
        private ?string $id_massar,
        private string $full_name,
        private ?string $birth_date,
        private ?string $birth_place,
        private ?string $gender,
        private ?string $school_level,
        private ?string $phone_number,
        private ?string $address,
        private ?string $email,
        private ?string $responsable,
        private ?string $photo,
        private ?string $training_duration,
        private ?string $sector,
        private ?string $filières_formation,
        private string $training_level,
        private ?string $group_uuid,
        private ?string $monthly_amount,
        private ?string $registration_fee,
        private ?string $product,
        private ?string $frais_diplôme,
        private ?string $annual_amount,
        private ?string $status,
        private ?string $date_start_at,
        private ?string $date_fin_at,
    ) {}

    public function toArray(): array
    {
        $fullName = str_replace(' ', '', $this->full_name);
        $randomString = Str::random(5);
        $password = strtolower($fullName) . $randomString;
        return [
            'inscription_number' => $this->inscription_number,
            'CIN' => $this->CIN,
            'id_massar' => $this->id_massar,
            'full_name' => $this->full_name,
            'birth_date' => $this->birth_date,
            'birth_place' => $this->birth_place,
            'gender' => $this->gender,
            'school_level' => $this->school_level,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'email' => $this->email,
            'password' => Hash::make($password),
            'plain_password' => $password, 
            'responsable' => $this->responsable,
            'photo' => $this->photo,
            'training_duration' => $this->training_duration,
            'sector' => $this->sector,
            'filières_formation' => $this->filières_formation,
            'training_level' => $this->training_level,
            'group_uuid' => $this->group_uuid,
            'monthly_amount' => $this->monthly_amount,
            'registration_fee' => $this->registration_fee,
            'product' => $this->product,
            'frais_diplôme' => $this->frais_diplôme,
            'annual_amount' => $this->annual_amount,
            'status' => $this->status,
            'date_start_at' => $this->date_start_at,
            'date_fin_at' => $this->date_fin_at,
        ];
    }
}
