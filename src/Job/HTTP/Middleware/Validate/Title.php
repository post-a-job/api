<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Validate;

use PostAJob\API\Job\HTTP\Middleware\RequestMiddlewareInterface;
use PostAJob\API\Job\ValueObject\Exception\TitleIsEmpty;
use PostAJob\API\Job\ValueObject\Exception\TitleIsTooLong;
use PostAJob\API\Job\ValueObject\Exception\TitleIsTooShort;
use PostAJob\API\Job\ValueObject\Title as TitleVO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Title implements MiddlewareInterface
{
    private const KEY = 'title';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $body = \json_decode((string) $request->getBody(), true) ?: [];
            $title = new TitleVO($body[self::KEY] ?? '');
        } catch (TitleIsEmpty | TitleIsTooShort | TitleIsTooLong$e) {
            $failures = $request->getAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, []);
            $failures[self::KEY] = $e->getMessage();
            $request = $request->withAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, $failures);

            return $handler->handle($request);
        }

        $request = $request->withAttribute(TitleVO::class, $title);

        return $handler->handle($request);
    }
}
