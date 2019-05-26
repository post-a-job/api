<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Validate;

use PostAJob\API\Job\HTTP\Middleware\RequestMiddlewareInterface;
use PostAJob\API\Job\ValueObject\Exception\ProgrammingLanguagesIsDuplicated;
use PostAJob\API\Job\ValueObject\Exception\ProgrammingLanguagesIsNotSupported;
use PostAJob\API\Job\ValueObject\ProgrammingLanguages as ProgrammingLanguagesVO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ProgrammingLanguages implements MiddlewareInterface
{
    private const KEY = 'programming_languages';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $body = \json_decode((string) $request->getBody(), true) ?: [];
            $programming_languages = new ProgrammingLanguagesVO($body[self::KEY] ?? '');
        } catch (ProgrammingLanguagesIsDuplicated | ProgrammingLanguagesIsNotSupported $e) {
            $failures = $request->getAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, []);
            $failures[self::KEY] = $e->getMessage();
            $request = $request->withAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, $failures);

            return $handler->handle($request);
        }

        $request = $request->withAttribute(ProgrammingLanguagesVO::class, $programming_languages);

        return $handler->handle($request);
    }
}
