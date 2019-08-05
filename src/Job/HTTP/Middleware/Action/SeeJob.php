<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Action;

use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Psr7\Response;
use PostAJob\API\Job\Job;
use PostAJob\API\Job\Repository\Exception\JobDoesNotExists;
use PostAJob\API\Job\UseCase\SeeJob\Command;
use PostAJob\API\Job\UseCase\SeeJob\Handler;
use PostAJob\API\Job\ValueObject\ID;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class SeeJob implements MiddlewareInterface
{
    /**
     * @var Handler
     */
    private $handler;

    public function __construct(Handler $handler)
    {
        $this->handler = $handler;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $command = new Command($request->getAttribute(ID::class));
        try {
            $job = ($this->handler)($command);
        } catch (JobDoesNotExists $e) {
            return new Response(StatusCodeInterface::STATUS_NOT_FOUND);
        }

        $request = $request->withAttribute(Job::class, $job);

        return $handler->handle($request);
    }
}
