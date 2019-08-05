<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use InvalidArgumentException;

final class ProgrammingLanguagesIsNotSupported extends InvalidArgumentException
{
    private const ERROR_MESSAGE = 'The programming language must be supported. The given programming language "%s" is not supported yet.';

    public function __construct(string $unsupportedLanguage)
    {
        parent::__construct(\sprintf(self::ERROR_MESSAGE, $unsupportedLanguage));
    }
}
