<?php

declare(strict_types=1);

namespace PostAJob\API\Probe\HTTP\Middleware\Action;

use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Readiness implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Response(StatusCodeInterface::STATUS_OK, [], \json_encode(['API are ready']));
    }
}
