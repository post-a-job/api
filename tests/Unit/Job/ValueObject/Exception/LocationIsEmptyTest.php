<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use PostAJob\API\TestCase;

final class LocationIsEmptyTest extends TestCase
{
    /**
     * @test
     */
    public function should_return_expected_error_message(): void
    {
        $exception = new LocationIsEmpty();
        $this->assertSame('The locations must not be empty.', $exception->getMessage());
    }

    /**
     * @test
     */
    public function should_return_zero_as_code(): void
    {
        $exception = new LocationIsEmpty();
        $this->assertSame(0, $exception->getCode());
    }
}
