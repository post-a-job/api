<?php

declare(strict_types=1);

namespace PostAJob\API\Job\UseCase\SeeJob;

use PostAJob\API\Job\Job;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureGettingAJob;
use PostAJob\API\Job\Repository\JobRepository;
use PostAJob\API\Job\UseCase\SeeJob\Exception\UnexpectedFailureSeeingAJob;

final class Handler
{
    /**
     * @var JobRepository
     */
    private $repository;

    public function __construct(JobRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws UnexpectedFailureSeeingAJob
     */
    public function __invoke(Command $command): Job
    {
        try {
            return $this->repository->get($command->ID());
        } catch (UnexpectedFailureGettingAJob $e) {
            throw new UnexpectedFailureSeeingAJob($command->ID(), $e);
        }
    }
}
