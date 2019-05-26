<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use Money\Money;
use PostAJob\API\Job\ValueObject\Exception\SalaryHasDifferentCurrencies;
use PostAJob\API\Job\ValueObject\Exception\SalaryMinIsGreaterThenMax;

final class Salary
{
    /**
     * @var Money
     */
    private $min;

    /**
     * @var Money
     */
    private $max;

    /**
     * @throws SalaryMinIsGreaterThenMax
     * @throws SalaryHasDifferentCurrencies
     */
    public function __construct(Money $min, Money $max)
    {
        try {
            if ($min->greaterThan($max)) {
                throw new SalaryMinIsGreaterThenMax($min, $max);
            }
        } catch (SalaryMinIsGreaterThenMax $e) {
            throw $e;
        } catch (\InvalidArgumentException $e) {
            throw new SalaryHasDifferentCurrencies($min->getCurrency(), $max->getCurrency(), $e);
        }
        $this->min = $min;
        $this->max = $max;
    }

    public function min(): Money
    {
        return $this->min;
    }

    public function max(): Money
    {
        return $this->max;
    }

    public function equals(self $salary): bool
    {
        if (!$this->min->getCurrency()->equals($salary->min->getCurrency())) {
            return false;
        }

        if (!$this->min->equals($salary->min)) {
            return false;
        }

        if (!$this->max->equals($salary->max)) {
            return false;
        }

        return true;
    }

    public function toArray(): array
    {
        return [
            'min' => "{$this->min->getAmount()} {$this->min->getCurrency()->getCode()}",
            'max' => "{$this->max->getAmount()} {$this->max->getCurrency()->getCode()}",
        ];
    }

    private function __clone()
    {
    }
}
