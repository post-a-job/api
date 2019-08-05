<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use InvalidArgumentException;

final class ProgrammingLanguagesIsEmpty extends InvalidArgumentException
{
    private const ERROR_MESSAGE = 'The programming language must not be empty.';

    public function __construct()
    {
        parent::__construct(self::ERROR_MESSAGE);
    }
}
