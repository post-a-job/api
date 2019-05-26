<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use InvalidArgumentException;

final class ProgrammingLanguagesIsDuplicated extends InvalidArgumentException
{
    private const ERROR_MESSAGE = 'The programming languages must be unique in the list. The given programming languages "%s" is specified more than once.';

    public function __construct(string $programmingLanguage)
    {
        parent::__construct(\sprintf(self::ERROR_MESSAGE, $programmingLanguage));
    }
}
