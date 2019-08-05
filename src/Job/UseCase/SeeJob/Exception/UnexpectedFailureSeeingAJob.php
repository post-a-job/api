<?php

declare(strict_types=1);

namespace PostAJob\API\Job\UseCase\SeeJob\Exception;

use PostAJob\API\Job\ValueObject\ID;
use RuntimeException;
use Throwable;

final class UnexpectedFailureSeeingAJob extends RuntimeException
{
    private const ERROR_MESSAGE = 'An unexpected failure seeing a job with ID "%s"';

    public function __construct(ID $ID, Throwable $previous)
    {
        parent::__construct(\sprintf(self::ERROR_MESSAGE, $ID), 0, $previous);
    }
}
