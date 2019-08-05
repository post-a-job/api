<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Repository;

use PostAJob\API\Job\Job;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureAddingAJob;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureGettingAJob;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureRetrievingNextID;
use PostAJob\API\Job\ValueObject\ID;

interface JobRepository
{
    /**
     * @throws UnexpectedFailureRetrievingNextID
     */
    public function nextID(): ID;

    /**
     * @throws UnexpectedFailureAddingAJob
     */
    public function add(Job $job): void;

    /**
     * @throws UnexpectedFailureGettingAJob
     */
    public function get(ID $ID): Job;
}
