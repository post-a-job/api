<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Action;

use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Psr7\Response;
use PostAJob\API\Job\UseCase\PostNewJob\Command;
use PostAJob\API\Job\UseCase\PostNewJob\Handler;
use PostAJob\API\Job\ValueObject\Company;
use PostAJob\API\Job\ValueObject\Description;
use PostAJob\API\Job\ValueObject\Locations;
use PostAJob\API\Job\ValueObject\ProgrammingLanguages;
use PostAJob\API\Job\ValueObject\Salary;
use PostAJob\API\Job\ValueObject\Title;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class PostJob implements MiddlewareInterface
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
        $command = new Command(
            $request->getAttribute(Title::class),
            $request->getAttribute(Description::class),
            $request->getAttribute(Salary::class),
            $request->getAttribute(Company::class),
            $request->getAttribute(Locations::class),
            $request->getAttribute(ProgrammingLanguages::class),
            );

        $id = ($this->handler)($command);

        return new Response(StatusCodeInterface::STATUS_CREATED, ['Location' => "/jobs/$id"]);
    }
}
