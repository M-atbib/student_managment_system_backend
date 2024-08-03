<?php 


declare(strict_types=1);
namespace App\DataTransferObjects\group;


class GroupDataObject
{
    
    public function __construct(
        private string $name,
        private string $etab_uuid,
    ) {}

    public function toArray(): array
    {
        return [
           'name' => $this->name,
           'etab_uuid' => $this->etab_uuid
        ];
    }
}
