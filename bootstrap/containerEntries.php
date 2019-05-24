<?php

declare(strict_types=1);

use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Monolog\Logger;
use Moon\Container\Container;
use Moon\Moon\Handler\ErrorHandlerInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

$entries = [];

$entries['settings'] = [
    'pathLogsDirectory' => getenv('PATH_LOGS_DIRECTORY'),
];

$entries[ServerRequestInterface::class] = function () {
    return ServerRequest::fromGlobals();
};

$entries[ResponseInterface::class] = function () {
    return new Response();
};

$entries[LoggerInterface::class] = function (ContainerInterface $container) {
    $file = __DIR__ . "/../{$container->get('settings')['pathLogsDirectory']}app.log";

    return new Logger('app', [new \Monolog\Handler\RotatingFileHandler($file)]);
};

$entries[ErrorHandlerInterface::class] = function (ContainerInterface $container) {
    return new class($container->get(LoggerInterface::class)) implements ErrorHandlerInterface
    {
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
            $this->logger->error($e->getMessage(), ['stack' => $e]);

            return $response->withStatus(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        }
    };
};

return $entries;
