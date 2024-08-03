<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'type' => $this->type,
            'methode' => $this->methode,
            'montant' => $this->montant,
            'month'=> $this->month,
            'date_payment' => $this->date_payment
        ];
    }
}
