<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use InvalidArgumentException;
use Money\Currency;
use Throwable;

final class SalaryHasDifferentCurrencies extends InvalidArgumentException
{
    private const ERROR_MESSAGE = 'The currencies must be equal. Given "%s" and "%s".';

    public function __construct(Currency $currency1, Currency $currency2, Throwable $previous)
    {
        parent::__construct(\sprintf(self::ERROR_MESSAGE, $currency1, $currency2), 0, $previous);
    }
}
