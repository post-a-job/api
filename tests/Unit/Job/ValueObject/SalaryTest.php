<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use Error;
use Money\Currency;
use Money\Money;
use PostAJob\API\Job\ValueObject\Exception\SalaryHasDifferentCurrencies;
use PostAJob\API\Job\ValueObject\Exception\SalaryMinIsGreaterThenMax;
use PostAJob\API\TestCase;

final class SalaryTest extends TestCase
{
    /**
     * @test
     */
    public function should_thrown_a_salary_has_different_currencies_exception(): void
    {
        $this->expectException(SalaryHasDifferentCurrencies::class);
        $min = new Money(1, new Currency('EUR'));
        $max = new Money(1, new Currency('USD'));
        new Salary($min, $max);
    }

    /**
     * @test
     */
    public function should_thrown_a_salary_min_is_greater_then_max_exception(): void
    {
        $this->expectException(SalaryMinIsGreaterThenMax::class);
        $min = new Money(10, new Currency('EUR'));
        $max = new Money(9, new Currency('EUR'));
        new Salary($min, $max);
    }

    /**
     * @test
     */
    public function should_thrown_an_exception_because_is_impossible_to_clone(): void
    {
        $this->expectException(Error::class);
        $min = new Money(10, new Currency('EUR'));
        $max = new Money(10, new Currency('EUR'));
        $salary = new Salary($min, $max);
        clone $salary;
    }

    /**
     * @test
     */
    public function should_return_true_when_comparing_equal_objects(): void
    {
        $min = new Money(10, new Currency('EUR'));
        $max = new Money(10, new Currency('EUR'));
        $salary1 = new Salary($min, $max);
        $salary2 = new Salary($min, $max);
        $this->assertTrue($salary1->equals($salary2));
    }

    /**
     * @test
     */
    public function should_return_false_when_comparing_different_objects(): void
    {
        $min1 = new Money(10, new Currency('EUR'));
        $max1 = new Money(100, new Currency('EUR'));
        $min2 = new Money(9, new Currency('EUR'));
        $max2 = new Money(99, new Currency('EUR'));
        $salary1 = new Salary($min1, $max1);
        $salary2 = new Salary($min2, $max2);
        $this->assertFalse($salary1->equals($salary2));
    }

    /**
     * @test
     */
    public function should_return_the_same_property_that_has_been_injected(): void
    {
        $min = new Money(10, new Currency('EUR'));
        $max = new Money(100, new Currency('EUR'));
        $salary = new Salary($min, $max);
        $this->assertSame($min, $salary->min());
        $this->assertSame($max, $salary->max());
    }
}
