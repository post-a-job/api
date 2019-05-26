<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use Exception;
use Money\Currency;
use PostAJob\API\TestCase;
use Throwable;

final class SalaryHasDifferentCurrenciesTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function should_return_expected_error_message(Currency $currency1, Currency $currency2, Throwable $previous): void
    {
        $exception = new SalaryHasDifferentCurrencies($currency1, $currency2, $previous);
        $this->assertSame(
            "The currencies must be equal. Given \"{$currency1->getCode()}\" and \"{$currency2->getCode()}\".",
            $exception->getMessage()
        );
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function should_return_zero_as_code(Currency $currency1, Currency $currency2, Throwable $previous): void
    {
        $exception = new SalaryHasDifferentCurrencies($currency1, $currency2, $previous);
        $this->assertSame(0, $exception->getCode());
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function should_have_the_same_previous_exception(Currency $currency1, Currency $currency2, Throwable $previous): void
    {
        $exception = new SalaryHasDifferentCurrencies($currency1, $currency2, $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function dataProvider(): array
    {
        return [
            [
                'currency one' => new Currency('USD'),
                'currency two' => new Currency('EUR'),
                'previous' => new Exception('previous error'),
            ],
        ];
    }
}
