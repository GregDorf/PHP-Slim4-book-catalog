<?php

namespace BookCatalog\DTO;

readonly class BookDTO
{
    public ?int $id;
    public string $title;
    public string $author;
    public int $year;
    public ?bool $is_active;

    public function __construct(?int $id, string $title, string $author, int $year, ?bool $is_active) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->is_active = $is_active;
    }

    public static function fromArray(array $data): self {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            title: $data['title'] ?? '',
            author: $data['author'] ?? '',
            year: isset($data['year']) ? (int) $data['year'] : 0,
            is_active: isset($data['is_active']) ? (bool) $data['is_active'] : true,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'year' => $this->year,
        ];
    }
}