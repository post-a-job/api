<?php

declare(strict_types=1);

namespace PostAJob\API\Probe\HTTP\Middleware\Action;

use PostAJob\API\Probe\HTTP\Middleware\RequestMiddlewareInterface;
use PostAJob\API\Probe\HTTP\Service\BuildResponse\BuildResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Liveness implements MiddlewareInterface
{
    /**
     * @var BuildResponse
     */
    private $buildResponse;

    public function __construct(BuildResponse $buildResponse)
    {
        $this->buildResponse = $buildResponse;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return ($this->buildResponse)($request->withAttribute(RequestMiddlewareInterface::API_ATTRIBUTE_NAME, ['API' => 'Live']));
    }
}
