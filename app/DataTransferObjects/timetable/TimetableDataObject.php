<?php 


declare(strict_types=1);
namespace App\DataTransferObjects\timetable;


class TimetableDataObject
{
    
    public function __construct(
        private string $title,
        private string $group_uuid,
        private string $name_file
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'group_uuid' => $this->group_uuid,
            'name_file' => $this->name_file
        ];
    }
}
