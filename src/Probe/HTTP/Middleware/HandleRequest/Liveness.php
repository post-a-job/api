<?php

declare(strict_types=1);

namespace PostAJob\API\Probe\HTTP\Middleware\HandleRequest;

use Moon\HttpMiddleware\Delegate;
use PostAJob\API\Probe\HTTP\Middleware\Action\Liveness as LivenessAction;
use PostAJob\API\Probe\HTTP\Middleware\Check\Database;
use Psr\Container\ContainerInterface;

final class Liveness extends Delegate
{
    public function __construct(array $stages, ContainerInterface $container)
    {
        $stages = \array_merge($stages, [Database::class, LivenessAction::class]);
        parent::__construct($stages, static function () {
        }, $container);
    }
}
