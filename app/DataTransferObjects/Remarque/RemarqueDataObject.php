<?php 


declare(strict_types=1);
namespace App\DataTransferObjects\Remarque;


class RemarqueDataObject
{
    
    public function __construct(
        private string $text,
        private string $student_uuid,
    ) {}

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'student_uuid' => $this->student_uuid
        ];
    }
}
