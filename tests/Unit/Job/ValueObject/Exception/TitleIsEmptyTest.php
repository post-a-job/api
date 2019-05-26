<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use PostAJob\API\TestCase;

final class TitleIsEmptyTest extends TestCase
{
    /**
     * @test
     */
    public function should_return_expected_error_message(): void
    {
        $exception = new TitleIsEmpty();
        $this->assertSame('The title must not be empty.', $exception->getMessage());
    }

    /**
     * @test
     */
    public function should_return_zero_as_code(): void
    {
        $exception = new TitleIsEmpty();
        $this->assertSame(0, $exception->getCode());
    }
}
