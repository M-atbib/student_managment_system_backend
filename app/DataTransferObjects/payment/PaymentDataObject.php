<?php

declare(strict_types=1);
namespace App\DataTransferObjects\payment;

use Illuminate\Support\Facades\Date;

class PaymentDataObject
{
    public function __construct(
        public string $student_uuid,
        public string $type,
        public string $methode,
        public ?string $month,
        public string $montant,
        public string $date_payment
    ) {}

    public function toArray(): array
    {
        return [
            'student_uuid' => $this->student_uuid,
            'type' => $this->type,
            'methode' => $this->methode,
            'montant' => $this->montant,
            'month' => $this->month,
            'date_payment' => $this->date_payment,
        ];
    }
}
