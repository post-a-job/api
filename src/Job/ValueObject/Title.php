<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use PostAJob\API\Job\ValueObject\Exception\TitleIsEmpty;
use PostAJob\API\Job\ValueObject\Exception\TitleIsTooLong;
use PostAJob\API\Job\ValueObject\Exception\TitleIsTooShort;

final class Title
{
    private const MIN_LENGTH = 5;
    private const MAX_LENGTH = 255;

    /**
     * @var string
     */
    private $value;

    /**
     * @throws TitleIsEmpty
     */
    public function __construct(string $value)
    {
        $value = \trim($value);
        if ('' === $value) {
            throw new TitleIsEmpty();
        }

        $length = \mb_strlen($value);
        if ($length < self::MIN_LENGTH) {
            throw new TitleIsTooShort(self::MIN_LENGTH);
        }

        if ($length > self::MAX_LENGTH) {
            throw new TitleIsTooLong(self::MAX_LENGTH);
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $title): bool
    {
        return $this->value === $title->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function __clone()
    {
    }
}
