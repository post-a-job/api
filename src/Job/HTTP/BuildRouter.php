<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP;

use Moon\Moon\Router;
use PostAJob\API\Job\HTTP\Middleware\RequestHandler\PostJob as PostJobRequestHandler;

final class BuildRouter
{
    private const PREFIX = '/jobs';

    public function __invoke(): Router
    {
        $router = new Router(self::PREFIX);
        $router->post('[/]', PostJobRequestHandler::class);

        return $router;
    }
}
