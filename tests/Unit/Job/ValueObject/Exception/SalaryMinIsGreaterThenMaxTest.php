<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use Money\Currency;
use Money\Money;
use PostAJob\API\TestCase;

final class SalaryMinIsGreaterThenMaxTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function should_return_expected_error_message(Money $money1, Money $money2): void
    {
        $exception = new SalaryMinIsGreaterThenMax($money1, $money2);
        $this->assertSame(
            "The min must be greater then the max one. Given min is \"{$money1->getAmount()}\", given max is \"{$money2->getAmount()}\".",
            $exception->getMessage()
        );
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function should_return_zero_as_code(Money $money1, Money $money2): void
    {
        $exception = new SalaryMinIsGreaterThenMax($money1, $money2);
        $this->assertSame(0, $exception->getCode());
    }

    public function dataProvider(): array
    {
        return [
            [
                'money one' => new Money(100, new Currency('EUR')),
                'money two' => new Money(90, new Currency('EUR')),
            ],
        ];
    }
}
