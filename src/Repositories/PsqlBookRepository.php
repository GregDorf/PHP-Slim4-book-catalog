<?php

namespace BookCatalog\Repositories;

use BookCatalog\DTO\BookDTO;
use PDO;

class PsqlBookRepository implements BookRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM books WHERE is_active = true ORDER BY id ASC");
        $rows = $stmt->fetchAll();

        return array_map(fn(array $row) => BookDTO::fromArray($row), $rows);
    }

    public function findById(int $id): ?BookDTO
    {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = :id AND is_active = true");
        $stmt->execute(['id' => $id]);
        $book = $stmt->fetch();

        if ($book === false) {
            return null;
        }

        return BookDTO::fromArray($book);
    }

    public function save(BookDTO $book): BookDTO
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO books (title, author, year) 
            VALUES (:title, :author, :year)
            RETURNING id, title, author, year
        ");

        $stmt->execute([
            'title' => $book->title,
            'author' => $book->author,
            'year' => $book->year,
        ]);

        $row = $stmt->fetch();

        return BookDTO::fromArray($row);
    }

    public function deleteById(int $id, ?bool $safe_del = true): bool
    {
        if ($safe_del) {
            $stmt = $this->pdo->prepare("UPDATE books SET is_active = false WHERE id = :id");
            $stmt->execute(['id' => $id]);
        }
        else {
            $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = :id");
            $stmt->execute(['id' => $id]);
        }

        return $stmt->rowCount() > 0;
    }
}