<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Validate;

use PostAJob\API\Job\HTTP\Middleware\RequestMiddlewareInterface;
use PostAJob\API\Job\ValueObject\Description as DescriptionVO;
use PostAJob\API\Job\ValueObject\Exception\DescriptionIsTooLong;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Description implements MiddlewareInterface
{
    private const KEY = 'description';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $body = \json_decode((string) $request->getBody(), true) ?: [];
            $description = new DescriptionVO($body[self::KEY] ?? '');
        } catch (DescriptionIsTooLong $e) {
            $failures = $request->getAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, []);
            $failures[self::KEY] = $e->getMessage();
            $request = $request->withAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, $failures);

            return $handler->handle($request);
        }
        $request = $request->withAttribute(DescriptionVO::class, $description);

        return $handler->handle($request);
    }
}
