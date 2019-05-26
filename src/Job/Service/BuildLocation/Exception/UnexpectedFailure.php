<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Service\BuildLocation\Exception;

use RuntimeException;
use Throwable;

final class UnexpectedFailure extends RuntimeException
{
    private const ERROR_MESSAGE = 'An unexpected failure happened with those locations "%s".';

    public function __construct(array $locations, Throwable $previous)
    {
        parent::__construct(\sprintf(self::ERROR_MESSAGE, \implode(', ', $locations)), 0, $previous);
    }
}
