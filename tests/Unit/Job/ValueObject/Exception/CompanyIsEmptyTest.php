<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use PostAJob\API\TestCase;

final class CompanyIsEmptyTest extends TestCase
{
    /**
     * @test
     */
    public function should_return_expected_error_message(): void
    {
        $exception = new CompanyIsEmpty();
        $this->assertSame('The company must not be empty.', $exception->getMessage());
    }

    /**
     * @test
     */
    public function should_return_zero_as_code(): void
    {
        $exception = new CompanyIsEmpty();
        $this->assertSame(0, $exception->getCode());
    }
}
