<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Repository\Instrumentation;

use PostAJob\API\Job\Job;
use PostAJob\API\Job\ValueObject\ID;
use Throwable;

interface Instrumentation
{
    public function nextIDWasRetrieved(ID $ID): void;

    public function nextIDWasNotRetrieved(Throwable $e): void;

    public function jobWasAdded(Job $job): void;

    public function jobWasNotAdded(Throwable $e): void;

    public function jobWasGet(ID $ID): void;

    public function jobWasNotGet(ID $ID, Throwable $e): void;

    public function rowWasMappedAsJob(array $row): void;

    public function rowWasNotMappedAsJob(array $row, Throwable $e): void;

    public function jobWasNotFound(ID $ID): void;
}
