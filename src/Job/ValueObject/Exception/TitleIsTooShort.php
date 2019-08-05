<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use InvalidArgumentException;

final class TitleIsTooShort extends InvalidArgumentException
{
    private const ERROR_MESSAGE = 'The title must be at least %d characters.';

    public function __construct(int $min)
    {
        parent::__construct(\sprintf(self::ERROR_MESSAGE, $min));
    }
}
