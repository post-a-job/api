<?php

declare(strict_types=1);

namespace PostAJob\API\Probe\HTTP\Middleware\RequestHandler;

use Moon\HttpMiddleware\Delegate;
use PostAJob\API\Probe\HTTP\Middleware\Action\Readiness as ReadinessAction;
use Psr\Container\ContainerInterface;

final class Readiness extends Delegate
{
    public function __construct(array $stages, ContainerInterface $container)
    {
        $stages = \array_merge($stages, [ReadinessAction::class]);
        parent::__construct($stages, static function () {
        }, $container);
    }
}
