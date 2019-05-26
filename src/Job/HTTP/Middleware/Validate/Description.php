<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Validate;

use PostAJob\API\Job\ValueObject\Description as DescriptionVO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Description implements MiddlewareInterface
{
    private const KEY = 'description';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $body = \json_decode((string) $request->getBody(), true) ?: [];
        $description = new DescriptionVO($body[self::KEY] ?? '');
        $request = $request->withAttribute(DescriptionVO::class, $description);

        return $handler->handle($request);
    }
}
