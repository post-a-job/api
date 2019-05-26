<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use Error;
use PostAJob\API\Job\ValueObject\Exception\IDIsInvalid;
use PostAJob\API\TestCase;

final class IDTest extends TestCase
{
    /**
     * @test
     */
    public function should_thrown_a_id_is_empty_exception(): void
    {
        $this->expectException(IDIsInvalid::class);
        ID::fromString('non-uuid');
    }

    /**
     * @test
     */
    public function should_thrown_an_exception_because_is_impossible_to_clone(): void
    {
        $this->expectException(Error::class);
        $id = ID::fromString('895352e8-a9d8-4589-a788-45d582bd2f15');
        clone $id;
    }

    /**
     * @test
     */
    public function should_return_true_when_comparing_equal_objects(): void
    {
        $id1 = ID::fromString('895352e8-a9d8-4589-a788-45d582bd2f15');
        $id2 = ID::fromString('895352e8-a9d8-4589-a788-45d582bd2f15');
        $this->assertTrue($id1->equals($id2));
    }

    /**
     * @test
     */
    public function should_return_false_when_comparing_different_objects(): void
    {
        $id1 = ID::fromString('895352e8-a9d8-4589-a788-45d582bd2f15');
        $id2 = ID::fromString('3a44c184-4817-48a2-bbfd-1132e6cd65af');
        $this->assertFalse($id1->equals($id2));
    }

    /**
     * @test
     */
    public function should_return_the_same_property_that_has_been_injected(): void
    {
        $id = ID::fromString('895352e8-a9d8-4589-a788-45d582bd2f15');
        $this->assertSame('895352e8-a9d8-4589-a788-45d582bd2f15', $id->value());
        $this->assertSame('895352e8-a9d8-4589-a788-45d582bd2f15', (string) $id);
    }
}
