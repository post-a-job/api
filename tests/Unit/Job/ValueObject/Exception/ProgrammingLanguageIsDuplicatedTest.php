<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use PostAJob\API\TestCase;

final class ProgrammingLanguageIsDuplicatedTest extends TestCase
{
    /**
     * @test
     */
    public function should_return_expected_error_message(): void
    {
        $exception = new ProgrammingLanguagesIsDuplicated('PHP');
        $this->assertSame(
            'The programming languages must be unique in the list. The given programming languages "PHP" is specified more than once.',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function should_return_zero_as_code(): void
    {
        $exception = new ProgrammingLanguagesIsDuplicated('PHP');
        $this->assertSame(0, $exception->getCode());
    }
}
