<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use InvalidArgumentException;
use Money\Money;

final class SalaryMinIsGreaterThenMax extends InvalidArgumentException
{
    private const ERROR_MESSAGE = 'The min must be greater then the max one. Given min is "%s", given max is "%s".';

    public function __construct(Money $min, Money $max)
    {
        parent::__construct(\sprintf(self::ERROR_MESSAGE, $min->getAmount(), $max->getAmount()));
    }
}
