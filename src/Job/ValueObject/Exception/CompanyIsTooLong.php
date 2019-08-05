<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use InvalidArgumentException;

final class CompanyIsTooLong extends InvalidArgumentException
{
    private const ERROR_MESSAGE = 'The company must be max %d characters.';

    public function __construct(int $max)
    {
        parent::__construct(\sprintf(self::ERROR_MESSAGE, $max));
    }
}
