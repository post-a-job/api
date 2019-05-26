<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use Exception;
use PostAJob\API\TestCase;
use Throwable;

final class IDIsInvalidTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function should_return_expected_error_message(string $value, Throwable $previous): void
    {
        $exception = new IDIsInvalid($value, $previous);
        $this->assertSame(
            "The ID must not be UUID compliant. The given ID \"$value\" is not valid.",
            $exception->getMessage()
        );
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function should_return_zero_as_code(string $value, Throwable $previous): void
    {
        $exception = new IDIsInvalid($value, $previous);
        $this->assertSame(0, $exception->getCode());
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function should_have_the_same_previous_exception(string $value, Throwable $previous): void
    {
        $exception = new IDIsInvalid($value, $previous);
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function dataProvider(): array
    {
        return [
            ['values' => 'invalid uuid string', 'previous' => new Exception('previous error')],
        ];
    }
}
