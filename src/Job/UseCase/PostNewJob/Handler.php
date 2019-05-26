<?php

declare(strict_types=1);

namespace PostAJob\API\Job\UseCase\PostNewJob;

use PostAJob\API\Job\Job;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureAddingAJob;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureRetrievingNextID;
use PostAJob\API\Job\Repository\JobRepository;
use PostAJob\API\Job\UseCase\PostNewJob\Exception\UnexpectedFailurePostingAJob;

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
     * @throws UnexpectedFailurePostingAJob
     */
    public function __invoke(Command $command): void
    {
        try {
            $id = $this->repository->nextID();
        } catch (UnexpectedFailureRetrievingNextID $e) {
            throw new UnexpectedFailurePostingAJob($e);
        }

        $job = Job::post(
            $id,
            $command->title(),
            $command->description(),
            $command->salary(),
            $command->company(),
            $command->locations(),
            $command->programmingLanguages()
        );

        try {
            $this->repository->add($job);
        } catch (UnexpectedFailureAddingAJob $e) {
            throw new UnexpectedFailurePostingAJob($e);
        }
    }
}
