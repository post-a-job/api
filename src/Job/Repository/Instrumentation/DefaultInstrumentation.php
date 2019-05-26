<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Repository\Instrumentation;

use PostAJob\API\Job\Job;
use PostAJob\API\Job\ValueObject\ID;
use Psr\Log\LoggerInterface;
use Throwable;

final class DefaultInstrumentation implements Instrumentation
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function nextIDWasRetrieved(ID $id): void
    {
        $this->logger->info('next id was retrieved', ['id' => (string) $id]);
    }

    public function nextIDWasNotRetrieved(Throwable $e): void
    {
        $this->logger->warning('next id was not retrieved', ['exception' => $e]);
    }

    public function jobWasAdded(Job $job): void
    {
        $this->logger->info('job was added', ['id' => (string) $job->id()]);
    }

    public function jobWasNotAdded(Throwable $e): void
    {
        $this->logger->error('job was not added', ['exception' => $e]);
    }
}
