<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use Error;
use PostAJob\API\Job\ValueObject\Exception\LocationIsEmpty;
use PostAJob\API\TestCase;
use ReflectionClass;

final class LocationTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidLocationValue
     */
    public function should_thrown_a_location_is_empty_exception(array $value): void
    {
        $this->expectException(LocationIsEmpty::class);
        $reflection = new ReflectionClass(Locations::class);
        $constructor = $reflection->getConstructor();
        $constructor->setAccessible(true);
        /** @var Locations $location */
        $location = $reflection->newInstanceWithoutConstructor();
        $constructor->invokeArgs($location, $value);
    }

    public function invalidLocationValue(): array
    {
        return [
            ['values' => ['']],
            ['values' => ['  ']],
        ];
    }

    /**
     * @test
     * @dataProvider validLocationValue
     */
    public function should_thrown_an_exception_because_is_impossible_to_clone(array $value): void
    {
        $this->expectException(Error::class);
        $reflection = new ReflectionClass(Locations::class);
        $constructor = $reflection->getConstructor();
        $constructor->setAccessible(true);
        /** @var Locations $location */
        $location = $reflection->newInstanceWithoutConstructor();
        $constructor->invokeArgs($location, $value);
        clone $location;
    }

    /**
     * @test
     * @dataProvider validLocationValue
     */
    public function should_return_true_when_comparing_equal_objects(array $value): void
    {
        $reflection = new ReflectionClass(Locations::class);
        $constructor = $reflection->getConstructor();
        $constructor->setAccessible(true);
        /** @var Locations $location1 */
        $location1 = $reflection->newInstanceWithoutConstructor();
        /** @var Locations $location2 */
        $location2 = $reflection->newInstanceWithoutConstructor();
        $constructor->invokeArgs($location1, $value);
        $constructor->invokeArgs($location2, $value);
        $this->assertTrue($location1->equals($location2));
    }

    /**
     * @test
     */
    public function should_return_false_when_comparing_different_objects(): void
    {
        $reflection = new ReflectionClass(Locations::class);
        $constructor = $reflection->getConstructor();
        $constructor->setAccessible(true);
        /** @var Locations $location1 */
        $location1 = $reflection->newInstanceWithoutConstructor();
        /** @var Locations $location2 */
        $location2 = $reflection->newInstanceWithoutConstructor();
        $constructor->invokeArgs($location1, ['Berlin']);
        $constructor->invokeArgs($location2, ['Rome']);
        $this->assertFalse($location1->equals($location2));
    }

    /**
     * @test
     * @dataProvider validLocationValue
     */
    public function should_return_the_same_property_that_has_been_injected(array $value, array $expectedValue): void
    {
        $reflection = new ReflectionClass(Locations::class);
        $constructor = $reflection->getConstructor();
        $constructor->setAccessible(true);
        /** @var Locations $location */
        $location = $reflection->newInstanceWithoutConstructor();
        $constructor->invokeArgs($location, $value);
        $this->assertSame($expectedValue, $location->toArray());
    }

    public function validLocationValue(): array
    {
        return [
            ['givenValue' => ['Rome', 'Milan'], 'expectedValue' => ['Rome', 'Milan']],
            ['givenValue' => ['Berlin'], 'expectedValue' => ['Berlin']],
        ];
    }
}
