<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Validate;

use PostAJob\API\Job\HTTP\Middleware\RequestMiddlewareInterface;
use PostAJob\API\Job\ValueObject\Company as CompanyVO;
use PostAJob\API\Job\ValueObject\Exception\CompanyIsEmpty;
use PostAJob\API\Job\ValueObject\Exception\CompanyIsTooLong;
use PostAJob\API\Job\ValueObject\Exception\CompanyIsTooShort;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Company implements MiddlewareInterface
{
    private const KEY = 'company';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $body = \json_decode((string) $request->getBody(), true) ?: [];
            $company = new CompanyVO($body[self::KEY] ?? '');
        } catch (CompanyIsEmpty | CompanyIsTooShort | CompanyIsTooLong $e) {
            $failures = $request->getAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, []);
            $failures[self::KEY] = $e->getMessage();
            $request = $request->withAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, $failures);

            return $handler->handle($request);
        }

        $request = $request->withAttribute(CompanyVO::class, $company);

        return $handler->handle($request);
    }
}
