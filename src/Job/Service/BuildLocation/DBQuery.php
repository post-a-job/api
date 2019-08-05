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
    /**
     * @var BuildLocation
     */
    private $buildLocation;

    public function __construct(Connection $connection, BuildLocation $buildLocation, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->buildLocation = $buildLocation;
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

        return ($this->buildLocation)($cities);
    }
}
