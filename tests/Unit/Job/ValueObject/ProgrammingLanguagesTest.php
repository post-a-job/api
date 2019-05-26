<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use Error;
use PostAJob\API\Job\ValueObject\Exception\ProgrammingLanguagesIsDuplicated;
use PostAJob\API\Job\ValueObject\Exception\ProgrammingLanguagesIsNotSupported;
use PostAJob\API\TestCase;

final class ProgrammingLanguagesTest extends TestCase
{
    /**
     * @test
     */
    public function should_thrown_a_programming_language_is_duplicated_exception(): void
    {
        $this->expectException(ProgrammingLanguagesIsDuplicated::class);
        new ProgrammingLanguages('PHP', 'PHP');
    }

    /**
     * @test
     */
    public function should_thrown_a_programming_language_is_not_supported_exception(): void
    {
        $this->expectException(ProgrammingLanguagesIsNotSupported::class);
        new ProgrammingLanguages('ELIXIR');
    }

    /**
     * @test
     */
    public function should_thrown_an_exception_because_is_impossible_to_clone(): void
    {
        $this->expectException(Error::class);
        $programmingLanguages = new ProgrammingLanguages('PHP');
        clone $programmingLanguages;
    }

    /**
     * @test
     */
    public function should_return_true_when_comparing_equal_objects(): void
    {
        $programmingLanguages1 = new ProgrammingLanguages('PHP', 'JAVASCRIPT');
        $programmingLanguages2 = new ProgrammingLanguages('PHP', 'JAVASCRIPT');
        $this->assertTrue($programmingLanguages1->equals($programmingLanguages2));
    }

    /**
     * @test
     */
    public function should_return_false_when_comparing_different_objects(): void
    {
        $programmingLanguages1 = new ProgrammingLanguages('PHP', 'JAVASCRIPT');
        $programmingLanguages2 = new ProgrammingLanguages('PHP', 'JAVASCRIPT');
        $this->assertTrue($programmingLanguages1->equals($programmingLanguages2));
    }

    /**
     * @test
     */
    public function should_return_the_same_property_that_has_been_injected(): void
    {
        $programmingLanguages = new ProgrammingLanguages('PHP', 'JAVASCRIPT');
        $this->assertSame(['PHP', 'JAVASCRIPT'], $programmingLanguages->toArray());
    }
}
