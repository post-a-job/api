<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Repository\Exception;

use PostAJob\API\Job\ValueObject\ID;
use RuntimeException;
use Throwable;

final class UnexpectedFailureGettingAJob extends RuntimeException
{
    private const ERROR_MESSAGE = 'An unexpected failure getting a job with ID "%s" to the repository.';

    public function __construct(ID $ID, Throwable $previous)
    {
        parent::__construct(\sprintf(self::ERROR_MESSAGE, $ID), 0, $previous);
    }
}
