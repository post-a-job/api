<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use PostAJob\API\Job\ValueObject\Exception\DescriptionIsTooLong;

final class Description
{
    private const MAX_LENGTH = 255;

    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $value = \trim($value);

        if (\mb_strlen($value) > self::MAX_LENGTH) {
            throw new DescriptionIsTooLong(self::MAX_LENGTH);
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $description): bool
    {
        return $this->value === $description->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function __clone()
    {
    }
}
