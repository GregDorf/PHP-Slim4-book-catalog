<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use BookCatalog\Services\BookService;
use BookCatalog\Repositories\BookRepositoryInterface;
use BookCatalog\DTO\BookDTO;
use InvalidArgumentException;

class BookServiceTest extends TestCase {
    private $bookRepositoryMock;
    private BookService $bookService;

    protected function setUp(): void {
        parent::setUp();

        $this->bookRepositoryMock = $this->createMock(BookRepositoryInterface::class);
        $this->bookService = new BookService($this->bookRepositoryMock);
    }

    public function testGetAllBooksReturnsArrayOfDTOs(): void {
        $expectedDTOs = [
            new BookDTO(1, 'Книга 1', 'Автор 1', 2020, true),
            new BookDTO(2, 'Книга 2', 'Автор 2', 2019, true),
        ];

        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedDTOs);

        $result = $this->bookService->getAllBooks();

        $this->assertEquals($expectedDTOs, $result);
    }

    public function testGetBookByIdReturnsDTOWhenFound(): void
    {
        $expectedBook = new BookDTO(1, 'Чистый код', 'Роберт Мартин', 2008, true);

        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($expectedBook);

        $result = $this->bookService->getBookById(1);

        $this->assertEquals($expectedBook, $result);
    }

    public function testGetBookByIdReturnsNullWhenNotFound(): void
    {
        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with(999)
            ->willReturn(null);

        $result = $this->bookService->getBookById(999);

        $this->assertNull($result);
    }

    public function testCreateBookThrowsExceptionOnEmptyTitle(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $invalidData = [
            'title' => '',
            'author' => 'Мартин Фаулер',
            'year' => 2018,
        ];

        $this->bookService->createBook($invalidData);
    }

    public function testCreateBookSuccessfullySavesValidData(): void
    {
        $inputData = [
            'title' => 'Рефакторинг',
            'author' => 'Мартин Фаулер',
            'year' => 2018,
        ];

        $savedDTO = new BookDTO(1, 'Рефакторинг', 'Мартин Фаулер', 2018, true);

        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->willReturn($savedDTO);

        $result = $this->bookService->createBook($inputData);

        $this->assertEquals($savedDTO, $result);
    }

    public function testDeleteBookCallsRepositoryAndReturnsResult(): void
    {
        $this->bookRepositoryMock
            ->expects($this->once())
            ->method('deleteById')
            ->with(1)
            ->willReturn(true);

        $result = $this->bookService->deleteBook(1);

        $this->assertTrue($result);
    }
}
