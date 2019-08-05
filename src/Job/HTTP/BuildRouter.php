<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP;

use Moon\Moon\Router;
use PostAJob\API\Job\HTTP\Middleware\HandleRequest\PostJob as PostJobRequestHandler;
use PostAJob\API\Job\HTTP\Middleware\HandleRequest\SeeJob as SeeJobRequestHandler;

final class BuildRouter
{
    private const UUID_REGEX = '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[34][0-9a-fA-F]{3}-[89ab][0-9a-fA-F]{3}-[0-9a-fA-F]{12}';

    public function __invoke(): Router
    {
        $router = new Router();
        $router->post('/jobs/[/]', PostJobRequestHandler::class);
        $router->get('::/jobs/(?<ID>'.self::UUID_REGEX.')', SeeJobRequestHandler::class);

        return $router;
    }
}
