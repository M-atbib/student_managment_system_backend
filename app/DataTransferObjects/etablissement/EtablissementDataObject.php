<?php 


declare(strict_types=1);


namespace App\DataTransferObjects\etablissement;

class EtablissementDataObject{

    public function __construct(
        private string $branch_name,
        private string $branch_logo,
    ){}


    public function toArray(): array{
        return[
            "branch_name"=> $this->branch_name,
            "branch_logo"=> $this->branch_logo,
        ];
    }
}