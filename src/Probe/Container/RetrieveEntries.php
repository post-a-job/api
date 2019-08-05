<?php

declare(strict_types=1);

namespace PostAJob\API\Probe\Container;

use PostAJob\API\Probe\HTTP\BuildRouter as BuildProbeRoute;
use PostAJob\API\Probe\HTTP\Middleware\Action\Liveness as LivenessAction;
use PostAJob\API\Probe\HTTP\Middleware\Action\Readiness as ReadinessAction;
use PostAJob\API\Probe\HTTP\Middleware\HandleRequest\Liveness as LivenessRequestHandler;
use PostAJob\API\Probe\HTTP\Middleware\HandleRequest\Readiness as ReadinessRequestHandler;
use Psr\Container\ContainerInterface;

final class RetrieveEntries
{
    /**
     * @var array
     */
    private $commonMiddlewares;

    public function __construct(array $commonMiddlewares)
    {
        $this->commonMiddlewares = $commonMiddlewares;
    }

    public function __invoke(): array
    {
        $entries = [];

        $entries[BuildProbeRoute::class] = static function (): BuildProbeRoute {
            return new BuildProbeRoute();
        };

        $entries[LivenessAction::class] = static function (): LivenessAction {
            return new LivenessAction();
        };

        $entries[LivenessRequestHandler::class] = function (ContainerInterface $container): LivenessRequestHandler {
            return new LivenessRequestHandler($this->commonMiddlewares, $container);
        };

        $entries[ReadinessAction::class] = static function (): ReadinessAction {
            return new ReadinessAction();
        };

        $entries[ReadinessRequestHandler::class] = function (ContainerInterface $container): ReadinessRequestHandler {
            return new ReadinessRequestHandler($this->commonMiddlewares, $container);
        };

        return $entries;
    }
}
