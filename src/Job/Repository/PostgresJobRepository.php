<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\ArrayType;
use PDO;
use PostAJob\API\Job\Job;
use PostAJob\API\Job\Repository\Exception\JobDoesNotExists;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureAddingAJob;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureGettingAJob;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureRetrievingNextID;
use PostAJob\API\Job\Repository\Instrumentation\Instrumentation;
use PostAJob\API\Job\Service\Map\ArrayToJob;
use PostAJob\API\Job\Service\Map\JobToArray;
use PostAJob\API\Job\ValueObject\ID;
use Ramsey\Uuid\Uuid;
use Throwable;

final class PostgresJobRepository implements JobRepository
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var JobToArray
     */
    private $mapJobToArray;
    /**
     * @var ArrayToJob
     */
    private $mapArrayToJob;
    /**
     * @var Instrumentation
     */
    private $instrumentation;

    public function __construct(
        Connection $connection,
        JobToArray $mapJobToArray,
        ArrayToJob $mapArrayToJob,
        Instrumentation $instrumentation
    ) {
        $this->connection = $connection;
        $this->mapJobToArray = $mapJobToArray;
        $this->mapArrayToJob = $mapArrayToJob;
        $this->instrumentation = $instrumentation;
    }

    /**
     * @throws UnexpectedFailureRetrievingNextID
     */
    public function nextID(): ID
    {
        try {
            $id = ID::fromString(Uuid::uuid4()->toString());
        } catch (Throwable $e) {
            $this->instrumentation->nextIDWasNotRetrieved($e);
            throw new UnexpectedFailureRetrievingNextID($e);
        }

        $this->instrumentation->nextIDWasRetrieved($id);

        return $id;
    }

    /**
     * @throws UnexpectedFailureAddingAJob
     */
    public function add(Job $job): void
    {
        try {
            $stmt = $this->connection->prepare(<<<EOF
INSERT INTO jobs
(id, title, description, company, locations, programming_languages, salary, posted_at,last_update)
VALUES (:id,:title,:description,:company,:locations,:programming_languages,:salary,:posted_at,:last_update)
EOF
            );
            $map = ($this->mapJobToArray)($job);
            $stmt->bindValue(':id', $map['id']);
            $stmt->bindValue(':title', $map['title']);
            $stmt->bindValue(':description', $map['description']);
            $stmt->bindValue(':company', $map['company']);
            $stmt->bindValue(':locations', $map['locations'], ArrayType::JSON);
            $stmt->bindValue(':programming_languages', $map['programming_languages'], ArrayType::JSON);
            $stmt->bindValue(':salary', $map['salary'], ArrayType::JSON);
            $stmt->bindValue(':posted_at', $map['posted_at']);
            $stmt->bindValue(':last_update', $map['last_update']);
            $stmt->execute();
        } catch (Throwable $e) {
            $this->instrumentation->jobWasNotAdded($e);
            throw new UnexpectedFailureAddingAJob($e);
        }

        $this->instrumentation->jobWasAdded($job);
    }

    /**
     * @throws UnexpectedFailureGettingAJob
     */
    public function get(ID $ID): Job
    {
        try {
            $rawID = (string) $ID;
            $stmt = $this->connection->prepare('SELECT * FROM jobs WHERE id = :id');
            $stmt->bindValue(':id', $rawID);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $this->instrumentation->jobWasNotGet($ID, $e);
            throw new UnexpectedFailureGettingAJob($ID, $e);
        }

        if (false === $row) {
            $this->instrumentation->jobWasNotFound($ID);
            throw new JobDoesNotExists($ID);
        }

        $this->instrumentation->jobWasGet($ID);
        try {
            $job = ($this->mapArrayToJob)($row);
            $this->instrumentation->rowWasMappedAsJob($row);
        } catch (Throwable $e) {
            $this->instrumentation->rowWasNotMappedAsJob($row, $e);
            throw new UnexpectedFailureGettingAJob($ID, $e);
        }

        return $job;
    }
}
