<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

final class Description
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $value = \trim($value);
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
