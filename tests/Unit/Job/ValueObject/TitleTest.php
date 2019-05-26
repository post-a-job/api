<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use Error;
use PostAJob\API\Job\ValueObject\Exception\TitleIsEmpty;
use PostAJob\API\TestCase;

final class TitleTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidTitleValue
     */
    public function should_thrown_a_title_is_empty_exception(string $value): void
    {
        $this->expectException(TitleIsEmpty::class);
        new Title($value);
    }

    public function invalidTitleValue(): array
    {
        return [
            ['values' => ''],
            ['values' => '  '],
        ];
    }

    /**
     * @test
     * @dataProvider validTitleValue
     */
    public function should_thrown_an_exception_because_is_impossible_to_clone(string $value): void
    {
        $this->expectException(Error::class);
        $title = new Title($value);
        clone $title;
    }

    /**
     * @test
     * @dataProvider validTitleValue
     */
    public function should_return_true_when_comparing_equal_objects(string $value): void
    {
        $title1 = new Title($value);
        $title2 = new Title($value);
        $this->assertTrue($title1->equals($title2));
    }

    /**
     * @test
     */
    public function should_return_false_when_comparing_different_objects(): void
    {
        $title1 = new Title('a');
        $title2 = new Title('b');
        $this->assertFalse($title1->equals($title2));
    }

    /**
     * @test
     * @dataProvider validTitleValue
     */
    public function should_return_the_same_property_that_has_been_injected(string $value, string $expectedValue): void
    {
        $title = new Title($value);
        $this->assertSame($expectedValue, $title->value());
        $this->assertSame($expectedValue, (string) $title);
    }

    public function validTitleValue(): array
    {
        return [
            ['givenValue' => 'Google', 'expectedValue' => 'Google'],
            ['givenValue' => ' Google ', 'expectedValue' => 'Google'],
        ];
    }
}
