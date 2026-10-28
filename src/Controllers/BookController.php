<?php

namespace BookCatalog\Controllers;

use BookCatalog\Services\BookService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use InvalidArgumentException;

class BookController
{
    private BookService $bookService;
    public function __construct(BookService $bookService) {
        $this->bookService = $bookService;
    }

    public function show(Request $request, Response $response): Response
    {
        $books = $this->bookService->getAllBooks();
        $data = array_map(fn($book) => $book->toArray(), $books);

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function find(Request $request, Response $response, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);
        $book = $this->bookService->getBookById($id);

        if ($book === null) {
            $response->getBody()->write(json_encode(['error' => 'Книга не найдена']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode($book->toArray()));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function save(Request $request, Response $response): Response
    {
        try {
            $body = (array)$request->getParsedBody();
            $createdBook = $this->bookService->createBook($body);

            $response->getBody()->write(json_encode($createdBook->toArray()));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        } catch (InvalidArgumentException $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(422)->withHeader('Content-Type', 'application/json');
        }
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);
        $success = $this->bookService->deleteBook($id);

        if (!$success) {
            $response->getBody()->write(json_encode(['error' => 'Книга не найдена для удаления']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        return $response->withStatus(204);
    }
}