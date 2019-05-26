<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Repository\Exception;

use RuntimeException;
use Throwable;

final class UnexpectedFailureRetrievingNextID extends RuntimeException
{
    private const ERROR_MESSAGE = 'An unexpected failure retrieving next id from the repository.';

    public function __construct(Throwable $previous)
    {
        parent::__construct(self::ERROR_MESSAGE, 0, $previous);
    }
}
