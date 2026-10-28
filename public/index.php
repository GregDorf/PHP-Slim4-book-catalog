<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use BookCatalog\Middleware\LoggingMiddleware;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
try {
    $container = $containerBuilder->build();
    AppFactory::setContainer($container);
} catch (Exception $e) {
    throw new RuntimeException("Ошибка конфигурации контейнера: " . $e->getMessage(), 0, $e);
}

$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->add(LoggingMiddleware::class);

$routes = (require __DIR__ . '/../config/routes.php');
$routes($app);

$app->run();
