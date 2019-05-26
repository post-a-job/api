<?php

declare(strict_types=1);

namespace PostAJob\API\Probe\HTTP;

use Moon\Moon\Router;
use PostAJob\API\Probe\HTTP\Middleware\RequestHandler\Liveness;
use PostAJob\API\Probe\HTTP\Middleware\RequestHandler\Readiness;

final class BuildRouter
{
    private const PREFIX = '/';

    public function __invoke(): Router
    {
        $router = new Router(self::PREFIX);
        $router->post('[live][/]', Liveness::class);
        $router->post('ready[/]', Readiness::class);

        return $router;
    }
}
