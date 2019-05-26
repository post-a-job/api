<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Validate;

use PostAJob\API\Job\HTTP\Middleware\RequestMiddlewareInterface;
use PostAJob\API\Job\Service\BuildLocation\BuildLocation;
use PostAJob\API\Job\Service\BuildLocation\Exception\LocationsDoNotExist;
use PostAJob\API\Job\Service\BuildLocation\Exception\UnexpectedFailure;
use PostAJob\API\Job\ValueObject\Exception\LocationIsEmpty;
use PostAJob\API\Job\ValueObject\Locations as LocationVO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Locations implements MiddlewareInterface
{
    private const KEY = 'locations';

    /**
     * @var BuildLocation
     */
    private $buildLocation;

    public function __construct(BuildLocation $buildLocation)
    {
        $this->buildLocation = $buildLocation;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $body = \json_decode((string) $request->getBody(), true) ?: [];
            $location = ($this->buildLocation)($body[self::KEY] ?? []);
        } catch (LocationIsEmpty | LocationsDoNotExist | UnexpectedFailure $e) {
            $failures = $request->getAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, []);
            $failures[self::KEY] = $e->getMessage();
            $request = $request->withAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, $failures);

            return $handler->handle($request);
        }

        $request = $request->withAttribute(LocationVO::class, $location);

        return $handler->handle($request);
    }
}
