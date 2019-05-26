<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Service\BuildLocation;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Query\QueryBuilder;
use PostAJob\API\Job\Service\BuildLocation\Exception\LocationsDoNotExist;
use PostAJob\API\Job\Service\BuildLocation\Exception\UnexpectedFailure;
use PostAJob\API\Job\ValueObject\Locations;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;

final class DBQuery implements BuildLocation
{
    /**
     * @var QueryBuilder
     */
    private $query;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->query = $connection->createQueryBuilder()->select()->from('cities')->where('name IN (?)')->setMaxResults(1);
    }

    public function __invoke(array $cities): Locations
    {
        try {
            if (1 !== $this->query->setParameter(0, $cities, Connection::PARAM_STR_ARRAY)->execute()->rowCount()) {
                throw new LocationsDoNotExist($cities);
            }
        } catch (DBALException $e) {
            $this->logger->warning('An error occurred with the database', ['exception' => $e]);
            throw new UnexpectedFailure($cities, $e);
        }

        try {
            $reflection = new ReflectionClass(Locations::class);
        } catch (ReflectionException $e) {
            throw new UnexpectedFailure($cities, $e);
        }

        $constructor = $reflection->getConstructor();
        $constructor->setAccessible(true);
        /** @var Locations $location */
        $location = $reflection->newInstanceWithoutConstructor();
        $constructor->invokeArgs($location, $cities);

        return $location;
    }
}
