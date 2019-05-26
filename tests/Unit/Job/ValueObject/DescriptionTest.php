<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use Error;
use PostAJob\API\TestCase;

final class DescriptionTest extends TestCase
{
    /**
     * @test
     * @dataProvider validDescriptionValue
     */
    public function should_thrown_an_exception_because_is_impossible_to_clone(string $value): void
    {
        $this->expectException(Error::class);
        $description = new Description($value);
        clone $description;
    }

    /**
     * @test
     * @dataProvider validDescriptionValue
     */
    public function should_return_true_when_comparing_equal_objects(string $value): void
    {
        $description1 = new Description($value);
        $description2 = new Description($value);
        $this->assertTrue($description1->equals($description2));
    }

    /**
     * @test
     */
    public function should_return_false_when_comparing_different_objects(): void
    {
        $description1 = new Description('a');
        $description2 = new Description('b');
        $this->assertFalse($description1->equals($description2));
    }

    /**
     * @test
     * @dataProvider validDescriptionValue
     */
    public function should_return_the_same_property_that_has_been_injected(string $value, string $expectedValue): void
    {
        $description = new Description($value);
        $this->assertSame($expectedValue, $description->value());
        $this->assertSame($expectedValue, (string) $description);
    }

    public function validDescriptionValue(): array
    {
        return [
            ['givenValue' => 'This is a description', 'expectedValue' => 'This is a description'],
            ['givenValue' => ' This is a description ', 'expectedValue' => 'This is a description'],
        ];
    }
}
