<?php

namespace App\Http\Resources\Student;

use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Payment\PaymentResource;
use App\Http\Resources\Presence\PresenceResource;
use App\Http\Resources\Remarque\RemarqueResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
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
            'password' => $this->plain_password,
            'responsable' => json_decode($this->responsable),
            'photo' => $this->photo,
            'training_duration' => $this->training_duration,
            'sector' => $this->sector,
            'filières_formation' => $this->filières_formation,
            'training_level' => $this->training_level,
            'monthly_amount' => $this->monthly_amount,
            'registration_fee' => $this->registration_fee,
            'product' => $this->product,
            'frais_diplôme' => $this->frais_diplôme,
            'annual_amount' => $this->annual_amount,
            'status' => $this->status,
            'date_start_at' => $this->date_start_at,
            'date_fin_at' => $this->date_fin_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'group' => GroupResource::collection($this->whenLoaded('groups')),
            'remarques' => RemarqueResource::collection($this->whenLoaded('remarques')),
            'documents' => DocumentResource::collection($this->whenLoaded('documents')),
            'presences' => PresenceResource::collection($this->whenLoaded('presences')),
        ];
    }
}
