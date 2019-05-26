<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use PostAJob\API\Job\ValueObject\Exception\CompanyIsEmpty;

final class Company
{
    /**
     * @var string
     */
    private $value;

    /**
     * @throws CompanyIsEmpty
     */
    public function __construct(string $value)
    {
        $value = \trim($value);
        if ('' === $value) {
            throw new CompanyIsEmpty();
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $company): bool
    {
        return $this->value === $company->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function __clone()
    {
    }
}
