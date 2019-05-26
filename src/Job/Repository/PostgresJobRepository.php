<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Repository;

use Doctrine\DBAL\Connection;
use PostAJob\API\Job\Job;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureAddingAJob;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureRetrievingNextID;
use PostAJob\API\Job\Repository\Instrumentation\Instrumentation;
use PostAJob\API\Job\ValueObject\ID;
use Ramsey\Uuid\Uuid;
use Throwable;

final class PostgresJobRepository implements JobRepository
{
    private const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var Instrumentation
     */
    private $instrumentation;

    public function __construct(Connection $connection, Instrumentation $instrumentation)
    {
        $this->connection = $connection;
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
            $this->connection->insert('jobs', $this->mapToArray($job));
        } catch (Throwable $e) {
            $this->instrumentation->jobWasNotAdded($e);
            throw new UnexpectedFailureAddingAJob($e);
        }

        $this->instrumentation->jobWasAdded($job);
    }

    /**
     * Can be extracted in a different object.
     */
    private function mapToArray(Job $job): array
    {
        return [
            'id' => $job->id()->value(),
            'title' => $job->title()->value(),
            'description' => $job->description()->value(),
            'salary' => \json_encode($job->salary()->toArray()),
            'company' => $job->company()->value(),
            'locations' => \json_encode($job->location()->toArray()),
            'programming_languages' => \json_encode($job->programmingLanguages()->toArray()),
            'posted_at' => $job->postedAt()->format(self::DATE_TIME_FORMAT),
            'last_update' => $job->lastUpdate() ? $job->lastUpdate()->format(self::DATE_TIME_FORMAT) : null,
        ];
    }
}
