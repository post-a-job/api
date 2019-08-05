<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Repository\Exception;

use PostAJob\API\Job\ValueObject\ID;
use RuntimeException;
use Throwable;

final class JobDoesNotExists extends RuntimeException
{
    private const ERROR_MESSAGE = "Job with ID '%s' doesn't exists.";

    public function __construct(ID $ID, Throwable $previous = null)
    {
        parent::__construct(\sprintf(self::ERROR_MESSAGE, $ID), 0, $previous);
    }
}
