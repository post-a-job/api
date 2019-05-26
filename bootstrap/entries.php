<?php

declare(strict_types=1);

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Monolog\Logger;
use Moon\HttpMiddleware\Exception\InvalidArgumentException as InvalidArgumentExceptionMiddleware;
use Moon\Moon\Handler\ErrorHandlerInterface;
use PostAJob\API\Job\Container\RetrieveEntries as JobEntries;
use PostAJob\API\Probe\Container\RetrieveEntries as ProbeEntries;
use PostAJob\API\Trace\Container\RetrieveEntries as TraceEntries;
use PostAJob\API\Trace\HTTP\Middleware\Action\Trace as TracingAction;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Sentry\ClientBuilder as SentryBuildClient;
use Sentry\Monolog\Handler as SentryHandler;
use Sentry\State\Hub;

$entries = [];

$entries['settings'] = [
    'log' => [
        'level' => \getenv('LOG_LEVEL'),
        'dsn' => \getenv('SENTRY_DSN'),
    ],
    'connection' => [
        'url' => \getenv('DATABASE_URL'),
    ],
];

$entries[ServerRequestInterface::class] = static function (): ServerRequestInterface {
    return ServerRequest::fromGlobals();
};

$entries[ResponseInterface::class] = static function (): ResponseInterface {
    return new Response();
};

$entries[LoggerInterface::class] = static function (ContainerInterface $container): LoggerInterface {
    $client = SentryBuildClient::create(['dsn' => $container->get('settings')['log']['dsn']])->getClient();
    $handler = new SentryHandler(new Hub($client), $container->get('settings')['log']['level']);
    /** @var TracingAction $tracing */
    $tracing = $container->get(TracingAction::class);
    $processor = static function ($record) use ($tracing) {
        foreach ($record['context'] as $key => $value) {
            if ('extra' !== $key) {
                $record['context']['extra'][$key] = $value;
            }
        }

        $record['context']['extra'][TracingAction::LABEL] = $tracing->getTraceID();

        return $record;
    };

    return new Logger('app', [$handler], [$processor]);
};

$entries[ErrorHandlerInterface::class] = static function (ContainerInterface $container): ErrorHandlerInterface {
    return new class($container->get(LoggerInterface::class)) implements ErrorHandlerInterface {
        /**
         * @var LoggerInterface
         */
        private $logger;

        public function __construct(LoggerInterface $logger)
        {
            $this->logger = $logger;
        }

        /**
         * {@inheritdoc}
         */
        public function __invoke(Throwable $e, ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
        {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            if ($e instanceof InvalidArgumentExceptionMiddleware) {
                $this->logger->error('an error occurred because of an invalid middleware', ['middleware' => $e->getInvalidMiddleware()]);
            }

            return $response->withStatus(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }
    };
};

$entries[Connection::class] = static function (ContainerInterface $container): Connection {
    return DriverManager::getConnection([
        'url' => $container->get('settings')['connection']['url'],
        'driver' => 'pdo_pgsql',
    ]);
};

$tracingEntries = new TraceEntries();
$probeEntries = new ProbeEntries([TracingAction::class]);
$jobEntries = new JobEntries(Connection::class, [TracingAction::class]);

return \array_merge($entries, $tracingEntries(), $probeEntries(), $jobEntries());
