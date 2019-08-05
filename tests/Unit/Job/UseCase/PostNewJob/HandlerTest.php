<?php

declare(strict_types=1);

namespace PostAJob\API\Job\UseCase\PostNewJob;

use DateTimeImmutable;
use Exception;
use PostAJob\API\Job\Job;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureAddingAJob;
use PostAJob\API\Job\Repository\Exception\UnexpectedFailureRetrievingNextID;
use PostAJob\API\Job\Repository\JobRepository;
use PostAJob\API\Job\UseCase\PostNewJob\Exception\UnexpectedFailurePostingAJob;
use PostAJob\API\Job\ValueObject\ID;
use PostAJob\API\TestCase;
use Prophecy\Argument;
use Throwable;

final class HandlerTest extends TestCase
{
    /**
     * @test
     */
    public function should_return_unexpected_failure_posting_a_job_when_an_error_occurs_retrieving_next_id_from_repository(): void
    {
        $repository = $this->prophesize(JobRepository::class);
        $repository->nextID()->willThrow(UnexpectedFailureRetrievingNextID::class);
        $repository->add(Argument::type(Job::class))->shouldNotBeCalled();
        $repository = $repository->reveal();

        $handler = new Handler($repository);

        /** @var Command $command */
        $command = $this->factoryFaker->instance(Command::class);
        try {
            $handler($command);
        } catch (Throwable $e) {
            $this->assertInstanceOf(UnexpectedFailurePostingAJob::class, $e);
            $this->assertSame('An unexpected failure posting a new job.', $e->getMessage());
            $this->assertInstanceOf(UnexpectedFailureRetrievingNextID::class, $e->getPrevious());
        }
    }

    /**
     * @test
     */
    public function should_return_unexpected_failure_posting_a_job_when_an_error_occurs_retrieving_adding_job_to_the_repository(): void
    {
        /** @var ID $ID */
        $ID = $this->factoryFaker->instance(ID::class);

        /** @var Command $command */
        $command = $this->factoryFaker->instance(Command::class);

        $repository = $this->prophesize(JobRepository::class);
        $repository->nextID()->willReturn($ID);
        $those = $this;
        $repository->add(Argument::type(Job::class))->will(function (array $params) use ($ID, $command, $those) {
            /** @var Job $job */
            $job = $params[0];
            $those->assertSame($ID, $job->ID());
            $those->assertSame($command->title(), $job->title());
            $those->assertSame($command->description(), $job->description());
            $those->assertSame($command->salary(), $job->salary());
            $those->assertSame($command->company(), $job->company());
            $those->assertSame($command->locations(), $job->location());
            $those->assertSame($command->programmingLanguages(), $job->programmingLanguages());
            $those->assertEqualsWithDelta(new DateTimeImmutable(), $job->postedAt(), 5);
            $those->assertNull($job->lastUpdate());
            throw new UnexpectedFailureAddingAJob(new Exception());
        });
        $repository = $repository->reveal();

        $handler = new Handler($repository);

        try {
            $handler($command);
        } catch (Throwable $e) {
            $this->assertInstanceOf(UnexpectedFailurePostingAJob::class, $e);
            $this->assertSame('An unexpected failure posting a new job.', $e->getMessage());
            $this->assertInstanceOf(UnexpectedFailureAddingAJob::class, $e->getPrevious());
        }
    }

    /**
     * @test
     */
    public function should_add_a_new_job_to_the_repository(): void
    {
        /** @var ID $ID */
        $ID = $this->factoryFaker->instance(ID::class);

        /** @var Command $command */
        $command = $this->factoryFaker->instance(Command::class);

        $repository = $this->prophesize(JobRepository::class);
        $repository->nextID()->willReturn($ID);
        $those = $this;
        $repository->add(Argument::type(Job::class))->will(function (array $params) use ($ID, $command, $those) {
            $job = $params[0];
            $those->assertSame($ID, $job->ID());
            $those->assertSame($command->title(), $job->title());
            $those->assertSame($command->description(), $job->description());
            $those->assertSame($command->salary(), $job->salary());
            $those->assertSame($command->company(), $job->company());
            $those->assertSame($command->locations(), $job->location());
            $those->assertSame($command->programmingLanguages(), $job->programmingLanguages());
            $those->assertEqualsWithDelta(new DateTimeImmutable(), $job->postedAt(), 5);
            $those->assertNull($job->lastUpdate());
        });
        $repository = $repository->reveal();

        $handler = new Handler($repository);
        $handler($command);
    }
}
