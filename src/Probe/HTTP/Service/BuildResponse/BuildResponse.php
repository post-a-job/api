<?php

declare(strict_types=1);

namespace PostAJob\API\Probe\HTTP\Service\BuildResponse;

use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Psr7\Response;
use PostAJob\API\Probe\HTTP\Middleware\RequestMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class BuildResponse
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $statusCode = StatusCodeInterface::STATUS_OK;
        if (null !== $request->getAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME)) {
            $statusCode = StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;
        }

        $checks = $request->getAttribute(RequestMiddlewareInterface::CHECK_ATTRIBUTE_NAME, []);
        $api = $request->getAttribute(RequestMiddlewareInterface::API_ATTRIBUTE_NAME, []);
        $body = \array_merge($api, $checks);

        return new Response($statusCode, ['Content-Type' => 'application/json'], \GuzzleHttp\json_encode($body));
    }
}
