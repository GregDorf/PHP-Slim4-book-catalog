<?php

use BookCatalog\Controllers\BookController;
use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode(['message' => 'Добро пожаловать в API книжного каталога, перейдите в /books для просмотра списка книг']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->group('/books', function ($group) {
        $group->get('', [BookController::class, 'show']);
        $group->get('/{id}', [BookController::class, 'find']);
        $group->post('', [BookController::class, 'save']);
        $group->delete('/{id}', [BookController::class, 'delete']);
    });
};
