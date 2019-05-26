<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use InvalidArgumentException;
use Throwable;

final class IDIsInvalid extends InvalidArgumentException
{
    private const ERROR_MESSAGE = 'The ID must not be UUID compliant. The given ID "%s" is not valid.';

    public function __construct(string $invalidID, Throwable $previous)
    {
        parent::__construct(\sprintf(self::ERROR_MESSAGE, $invalidID), 0, $previous);
    }
}
