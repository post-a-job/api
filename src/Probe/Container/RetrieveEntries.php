<?php

declare(strict_types=1);

namespace PostAJob\API\Probe\Container;

use Doctrine\DBAL\Connection;
use PostAJob\API\Probe\HTTP\BuildRouter as BuildProbeRoute;
use PostAJob\API\Probe\HTTP\Middleware\Action\Liveness as LivenessAction;
use PostAJob\API\Probe\HTTP\Middleware\Action\Readiness as ReadinessAction;
use PostAJob\API\Probe\HTTP\Middleware\Check\Database;
use PostAJob\API\Probe\HTTP\Middleware\HandleRequest\Liveness as LivenessRequestHandler;
use PostAJob\API\Probe\HTTP\Middleware\HandleRequest\Readiness as ReadinessRequestHandler;
use PostAJob\API\Probe\HTTP\Service\BuildResponse\BuildResponse;
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

        $entries[BuildResponse::class] = static function (): BuildResponse {
            return new BuildResponse();
        };

        $entries[LivenessAction::class] = static function (ContainerInterface $container): LivenessAction {
            return new LivenessAction($container->get(BuildResponse::class));
        };

        $entries[LivenessRequestHandler::class] = function (ContainerInterface $container): LivenessRequestHandler {
            return new LivenessRequestHandler($this->commonMiddlewares, $container);
        };

        $entries[ReadinessAction::class] = static function (ContainerInterface $container): ReadinessAction {
            return new ReadinessAction($container->get(BuildResponse::class));
        };

        $entries[ReadinessRequestHandler::class] = function (ContainerInterface $container): ReadinessRequestHandler {
            return new ReadinessRequestHandler($this->commonMiddlewares, $container);
        };

        $entries[Database::class] = function (ContainerInterface $container): Database {
            return new Database($container->get(Connection::class));
        };

        return $entries;
    }
}
