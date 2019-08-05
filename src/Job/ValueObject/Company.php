<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use PostAJob\API\Job\ValueObject\Exception\CompanyIsEmpty;
use PostAJob\API\Job\ValueObject\Exception\CompanyIsTooLong;
use PostAJob\API\Job\ValueObject\Exception\CompanyIsTooShort;

final class Company
{
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 255;

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

        $length = \mb_strlen($value);
        if ($length < self::MIN_LENGTH) {
            throw new CompanyIsTooShort(self::MIN_LENGTH);
        }

        if ($length > self::MAX_LENGTH) {
            throw new CompanyIsTooLong(self::MAX_LENGTH);
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
