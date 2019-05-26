<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Failure;

use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\Psr7\stream_for;
use PostAJob\API\Job\HTTP\Middleware\RequestMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class BadRequest implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($errors = $request->getAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME)) {
            return new Response(StatusCodeInterface::STATUS_BAD_REQUEST, [], stream_for(\json_encode($errors)));
        }

        return $handler->handle($request);
    }
}
