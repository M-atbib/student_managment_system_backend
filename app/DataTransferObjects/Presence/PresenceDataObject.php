<?php

declare(strict_types=1);
namespace App\DataTransferObjects\Presence;

class PresenceDataObject
{
    public function __construct(
        public string $title,
        public string $type,
        public bool $justification,
        public string $remarque,
        public string $date,
        public array $studentUuids,
    ) {}
 
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
            'justification' => $this->justification,
            'remarque' => $this->remarque,
            'date' => $this->date,
            'student_uuids' => json_encode($this->studentUuids),
        ];
    }
}
