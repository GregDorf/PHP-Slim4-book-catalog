<?php

namespace BookCatalog\Services;

use BookCatalog\DTO\BookDTO;
use BookCatalog\Repositories\BookRepositoryInterface;
use InvalidArgumentException;

class BookService
{
    private BookRepositoryInterface $bookRepository;
    public function __construct(BookRepositoryInterface $bookRepository) {
        $this->bookRepository = $bookRepository;
    }

    public function getAllBooks(): array
    {
        return $this->bookRepository->findAll();
    }

    public function getBookById(int $id): ?BookDTO
    {
        return $this->bookRepository->findById($id);
    }

    public function createBook(array $data): BookDTO
    {
        $this->validateBookData($data);

        $bookDTO = BookDTO::fromArray($data);
        return $this->bookRepository->save($bookDTO);
    }

    public function deleteBook(int $id): bool
    {
        return $this->bookRepository->deleteById($id);
    }

    private function validateBookData(array $data): void
    {
        $title = trim((string) ($data['title'] ?? ''));
        $author = trim((string) ($data['author'] ?? ''));
        $year = isset($data['year']) ? (int) $data['year'] : 0;

        if ($title === '' || $author === '' || $year === 0) {
            throw new InvalidArgumentException('Поля "название", "автор" и "год" обязательны для заполнения.');
        }

        $currentYear = (int) date('Y');
        if ($year > $currentYear) {
            throw new InvalidArgumentException('Год в книге не может быть будущим.');
        }
    }
}