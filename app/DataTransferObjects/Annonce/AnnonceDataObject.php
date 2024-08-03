<?php 


declare(strict_types=1);
namespace App\DataTransferObjects\Annonce;


class AnnonceDataObject
{
    
    public function __construct(
        private string $text,
        private string $etab_uuid,
        private string $sector,
        private string $date_validite
    ) {}

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'etab_uuid' => $this->etab_uuid,
            'sector' => $this->sector,
            'date_validite' => $this->date_validite
        ];
    }
}
