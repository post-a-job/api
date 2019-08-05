<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject\Exception;

use PostAJob\API\TestCase;

final class ProgrammingLanguageIsNotSupportedTest extends TestCase
{
    /**
     * @test
     */
    public function should_return_expected_error_message(): void
    {
        $exception = new ProgrammingLanguagesIsNotSupported('PHP');
        $this->assertSame(
            'The programming language must be supported. The given programming language "PHP" is not supported yet.',
            $exception->getMessage()
        );
    }

    /**
     * @test
     */
    public function should_return_zero_as_code(): void
    {
        $exception = new ProgrammingLanguagesIsNotSupported('PHP');
        $this->assertSame(0, $exception->getCode());
    }
}
