<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\RequestHandler;

use Moon\HttpMiddleware\Delegate;
use PostAJob\API\Job\HTTP\Middleware\Action\PostJob as PostJobAction;
use PostAJob\API\Job\HTTP\Middleware\Failure\BadRequest;
use PostAJob\API\Job\HTTP\Middleware\Failure\ServerError;
use PostAJob\API\Job\HTTP\Middleware\Validate\Company;
use PostAJob\API\Job\HTTP\Middleware\Validate\Description;
use PostAJob\API\Job\HTTP\Middleware\Validate\Locations;
use PostAJob\API\Job\HTTP\Middleware\Validate\ProgrammingLanguages;
use PostAJob\API\Job\HTTP\Middleware\Validate\Salary;
use PostAJob\API\Job\HTTP\Middleware\Validate\Title;
use Psr\Container\ContainerInterface;

final class PostJob extends Delegate
{
    public function __construct(array $stages, ContainerInterface $container)
    {
        parent::__construct(\array_merge($stages, [
            ServerError::class,
            Company::class,
            Description::class,
            Locations::class,
            ProgrammingLanguages::class,
            Salary::class,
            Title::class,
            BadRequest::class,
            PostJobAction::class,
        ]), static function () {
        }, $container);
    }
}
