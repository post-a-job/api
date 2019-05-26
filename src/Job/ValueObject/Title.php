<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use PostAJob\API\Job\ValueObject\Exception\TitleIsEmpty;

final class Title
{
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
            throw  new TitleIsEmpty();
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
