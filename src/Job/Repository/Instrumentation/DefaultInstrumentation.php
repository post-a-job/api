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

    public function nextIDWasRetrieved(ID $ID): void
    {
        $this->logger->info('next id was retrieved', ['id' => (string) $ID]);
    }

    public function nextIDWasNotRetrieved(Throwable $e): void
    {
        $this->logger->warning('next id was not retrieved', ['exception' => $e]);
    }

    public function jobWasAdded(Job $job): void
    {
        $this->logger->info('job was added', ['id' => (string) $job->ID()]);
    }

    public function jobWasNotAdded(Throwable $e): void
    {
        $this->logger->error('job was not added', ['exception' => $e]);
    }

    public function jobWasGet(ID $ID): void
    {
        $this->logger->info('job was get', ['id' => (string) $ID]);
    }

    public function jobWasNotGet(ID $ID, Throwable $e): void
    {
        $this->logger->warning('job was not get', ['id' => (string) $ID, 'exception' => $e]);
    }

    public function jobWasNotFound(ID $ID): void
    {
        $this->logger->warning('job was not found', ['id' => (string) $ID]);
    }

    public function rowWasMappedAsJob(array $row): void
    {
        $this->logger->info('row was mapped as job', ['row' => $row]);
    }

    public function rowWasNotMappedAsJob(array $row, Throwable $e): void
    {
        $this->logger->info('row was mapped as job', ['row' => $row, 'exception' => $e]);
    }
}
