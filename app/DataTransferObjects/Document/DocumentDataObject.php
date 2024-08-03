<?php 


declare(strict_types=1);
namespace App\DataTransferObjects\Document;


class DocumentDataObject
{
    
    public function __construct(
        private string $name_file,
        private string $student_uuid,
    ) {}

    public function toArray(): array
    {
        return [
            'name_file' => $this->name_file,
            'student_uuid' => $this->student_uuid
        ];
    }
}
