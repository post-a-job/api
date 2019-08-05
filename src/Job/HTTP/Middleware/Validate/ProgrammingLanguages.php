<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Validate;

use PostAJob\API\Job\HTTP\Middleware\RequestMiddlewareInterface;
use PostAJob\API\Job\ValueObject\Exception\ProgrammingLanguagesIsDuplicated;
use PostAJob\API\Job\ValueObject\Exception\ProgrammingLanguagesIsEmpty;
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
            $a = \is_array($body[self::KEY]) ? $body[self::KEY] : [];
            $programmingLanguages = new ProgrammingLanguagesVO(...$a);
        } catch (ProgrammingLanguagesIsDuplicated | ProgrammingLanguagesIsNotSupported | ProgrammingLanguagesIsEmpty $e) {
            $failures = $request->getAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, []);
            $failures[self::KEY] = $e->getMessage();
            $request = $request->withAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, $failures);

            return $handler->handle($request);
        }

        $request = $request->withAttribute(ProgrammingLanguagesVO::class, $programmingLanguages);

        return $handler->handle($request);
    }
}
