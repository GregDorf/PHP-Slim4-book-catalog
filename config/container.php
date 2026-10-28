<?php

use BookCatalog\Repositories\BookRepositoryInterface;
use BookCatalog\Repositories\PsqlBookRepository;

return [
    BookRepositoryInterface::class => DI\autowire(PsqlBookRepository::class),

    PDO::class => function () {
        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $db = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASS'];

        $dsn = "pgsql:host=$host;port=$port;dbname=$db";

        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
];