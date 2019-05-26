<?php

declare(strict_types=1);

namespace PostAJob\API\Trace\HTTP\Middleware\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\Uuid;

final class Trace implements MiddlewareInterface
{
    public const LABEL = 'X-Trace-ID';

    /**
     * @var string
     */
    private $traceID;

    public function __construct()
    {
        $this->traceID = Uuid::uuid4()->toString();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $traceHeader = $request->getHeader(self::LABEL);
        if (($traceID = \array_shift($traceHeader)) && null !== $traceID) {
            $this->traceID = $traceID;
        }

        $response = $handler->handle($request);

        return $response->withAddedHeader(self::LABEL, $this->traceID);
    }

    public function getTraceID(): string
    {
        return $this->traceID;
    }
}
