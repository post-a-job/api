<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Container;

use Doctrine\DBAL\Connection;
use PostAJob\API\Job\HTTP\BuildRouter as BuildJobRouter;
use PostAJob\API\Job\HTTP\Middleware\Action\PostJob as PostJobAction;
use PostAJob\API\Job\HTTP\Middleware\Failure\BadRequest as BadRequestMiddleware;
use PostAJob\API\Job\HTTP\Middleware\Failure\ServerError as ServerErrorMiddleware;
use PostAJob\API\Job\HTTP\Middleware\RequestHandler\PostJob as PostJobRequestHandler;
use PostAJob\API\Job\HTTP\Middleware\Validate\Company as CompanyValidationMiddleware;
use PostAJob\API\Job\HTTP\Middleware\Validate\Description as DescriptionValidationMiddleware;
use PostAJob\API\Job\HTTP\Middleware\Validate\Locations as LocationsValidationMiddleware;
use PostAJob\API\Job\HTTP\Middleware\Validate\ProgrammingLanguages as ProgrammingLanguagesValidationMiddleware;
use PostAJob\API\Job\HTTP\Middleware\Validate\Salary as SalaryValidationMiddleware;
use PostAJob\API\Job\HTTP\Middleware\Validate\Title as TitleValidationMiddleware;
use PostAJob\API\Job\Repository\Instrumentation\DefaultInstrumentation;
use PostAJob\API\Job\Repository\Instrumentation\Instrumentation;
use PostAJob\API\Job\Repository\PostgresJobRepository;
use PostAJob\API\Job\Service\BuildLocation\DBQuery;
use PostAJob\API\Job\UseCase\PostNewJob\Handler as PostNewJobHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class RetrieveEntries
{
    /**
     * @var array
     */
    private $commonMiddlewareClassNames;

    public function __construct(array $commonMiddlewareClassNames)
    {
        $this->commonMiddlewareClassNames = $commonMiddlewareClassNames;
    }

    public function __invoke(): array
    {
        $entries = [];

        $entries[BuildJobRouter::class] = static function (): BuildJobRouter {
            return new BuildJobRouter();
        };

        $entries[PostNewJobHandler::class] = static function (ContainerInterface $container): PostNewJobHandler {
            return new PostNewJobHandler(
                $container->get(PostgresJobRepository::class),
                );
        };

        $entries[PostJobRequestHandler::class] = function (ContainerInterface $container): PostJobRequestHandler {
            return new PostJobRequestHandler($this->commonMiddlewareClassNames, $container);
        };

        $entries[ServerErrorMiddleware::class] = static function (ContainerInterface $container): ServerErrorMiddleware {
            return new ServerErrorMiddleware($container->get(LoggerInterface::class));
        };

        $entries[BadRequestMiddleware::class] = static function (): BadRequestMiddleware {
            return new BadRequestMiddleware();
        };

        $entries[CompanyValidationMiddleware::class] = static function (): CompanyValidationMiddleware {
            return new CompanyValidationMiddleware();
        };

        $entries[DescriptionValidationMiddleware::class] = static function (): DescriptionValidationMiddleware {
            return new DescriptionValidationMiddleware();
        };

        $entries[LocationsValidationMiddleware::class] = static function (ContainerInterface $container): LocationsValidationMiddleware {
            return new LocationsValidationMiddleware(
                new DBQuery($container->get(Connection::class), $container->get(LoggerInterface::class))
            );
        };

        $entries[ProgrammingLanguagesValidationMiddleware::class] = static function (): ProgrammingLanguagesValidationMiddleware {
            return new ProgrammingLanguagesValidationMiddleware();
        };

        $entries[SalaryValidationMiddleware::class] = static function (): SalaryValidationMiddleware {
            return new SalaryValidationMiddleware();
        };

        $entries[TitleValidationMiddleware::class] = static function (): TitleValidationMiddleware {
            return new TitleValidationMiddleware();
        };

        $entries[PostJobAction::class] = static function (ContainerInterface $container): PostJobAction {
            return new PostJobAction($container->get(PostNewJobHandler::class));
        };

        $entries[Instrumentation::class] = static function (ContainerInterface $container): Instrumentation {
            return new DefaultInstrumentation($container->get(LoggerInterface::class));
        };

        $entries[PostgresJobRepository::class] = static function (ContainerInterface $container): PostgresJobRepository {
            return new PostgresJobRepository(
                $container->get(Connection::class),
                $container->get(Instrumentation::class),
                );
        };

        return $entries;
    }
}
