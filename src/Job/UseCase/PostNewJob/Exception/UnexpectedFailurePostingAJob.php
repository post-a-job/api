<?php

declare(strict_types=1);

namespace PostAJob\API\Job\UseCase\PostNewJob\Exception;

use RuntimeException;
use Throwable;

final class UnexpectedFailurePostingAJob extends RuntimeException
{
    private const ERROR_MESSAGE = 'An unexpected failure posting a new job.';

    public function __construct(Throwable $previous)
    {
        parent::__construct(self::ERROR_MESSAGE, 0, $previous);
    }
}
