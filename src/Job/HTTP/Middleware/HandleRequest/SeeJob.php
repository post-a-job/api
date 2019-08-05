<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\HandleRequest;

use Moon\HttpMiddleware\Delegate;
use PostAJob\API\Job\HTTP\Middleware\Action\SeeJob as SeeJobAction;
use PostAJob\API\Job\HTTP\Middleware\Failure\BadRequest;
use PostAJob\API\Job\HTTP\Middleware\Failure\ServerError;
use PostAJob\API\Job\HTTP\Middleware\Map\MapJob;
use PostAJob\API\Job\HTTP\Middleware\Validate\ID;
use Psr\Container\ContainerInterface;

final class SeeJob extends Delegate
{
    public function __construct(array $stages, ContainerInterface $container)
    {
        parent::__construct(\array_merge($stages, [
            ServerError::class,
            ID::class,
            BadRequest::class,
            SeeJobAction::class,
            MapJob::class,
        ]), static function () {
        }, $container);
    }
}
