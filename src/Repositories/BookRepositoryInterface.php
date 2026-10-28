<?php

namespace BookCatalog\Repositories;

use BookCatalog\DTO\BookDTO;

interface BookRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?BookDTO;

    public function save(BookDTO $book): BookDTO;

    public function deleteById(int $id, ?bool $safe_del = true): bool;
}