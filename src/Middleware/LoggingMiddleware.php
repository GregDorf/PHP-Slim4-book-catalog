<?php

namespace BookCatalog\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use  Psr\Http\Message\ResponseInterface;
use \Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoggingMiddleware implements Middleware
{
    public function process(ServerRequestInterface $request, RequestHandler $handler): ResponseInterface
    {
        $timestamp = date('Y-m-d H:i:s');
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();

        $startTime = microtime(true);
        $response = $handler->handle($request);
        $duration = round((microtime(true) - $startTime) * 1000, 2);

        $logDir = __DIR__ . '/../../logs';

        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $logLine = "[$timestamp] {$method} {$path} - {$duration}ms\n";
        file_put_contents($logDir . '/app.log', $logLine, FILE_APPEND);

        return $response;
    }
}
