<?php

namespace App\DTOs;

class TaskData
{
    public string $title;
    public ?string $description;
    public bool $completed;
    public array $tags;

    public function __construct(array $data)
    {
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->completed = $data['completed'] ?? false;
        $this->tags = $data['tags'] ?? [];
    }
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'completed' => $this->completed,
        ];
    }
}
