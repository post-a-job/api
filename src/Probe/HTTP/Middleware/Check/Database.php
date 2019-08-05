<?php

declare(strict_types=1);

namespace PostAJob\API\Probe\HTTP\Middleware\Check;

use Doctrine\DBAL\Connection;
use PostAJob\API\Probe\HTTP\Middleware\RequestMiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

final class Database implements MiddlewareInterface
{
    private const CHECK_KEY = 'Database';
    private const FAILURE_MESSAGE = 'Not reachable';
    private const SUCCESS_MESSAGE = 'Reachable';
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $status = self::SUCCESS_MESSAGE;
        try {
            if (!$this->connection->ping()) {
                $status = self::FAILURE_MESSAGE;
                $request = $request->withAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, true);
            }
        } catch (Throwable $e) {
            $status = self::FAILURE_MESSAGE;
            $request = $request->withAttribute(RequestMiddlewareInterface::FAILURE_ATTRIBUTE_NAME, true);
        }

        $checks = $request->getAttribute(RequestMiddlewareInterface::CHECK_ATTRIBUTE_NAME, []);
        $checks[self::CHECK_KEY] = $status;
        $request = $request->withAttribute(RequestMiddlewareInterface::CHECK_ATTRIBUTE_NAME, $checks);

        return $handler->handle($request);
    }
}
