<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Map;

use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Psr7\Response;
use PostAJob\API\Job\HTTP\Middleware\Map\Exception\AttributeIsNotJob;
use PostAJob\API\Job\Job as JobAggregate;
use PostAJob\API\Job\Service\Map\JobToArray;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MapJob implements MiddlewareInterface
{
    /**
     * @var JobToArray
     */
    private $map;

    public function __construct(JobToArray $map)
    {
        $this->map = $map;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $job = $request->getAttribute(JobAggregate::class);
        if (null === $job) {
            throw new AttributeIsNotJob(\get_class($job));
        }

        return new Response(
            StatusCodeInterface::STATUS_OK,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\json_encode(($this->map)($job))
        );
    }
}
