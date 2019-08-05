<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Service\BuildLocation;

use PostAJob\API\Job\Service\BuildLocation\Exception\UnexpectedFailure;
use PostAJob\API\Job\ValueObject\Locations;
use ReflectionClass;
use ReflectionException;

final class Reflection implements BuildLocation
{
    public function __invoke(array $cities): Locations
    {
        try {
            $reflection = new ReflectionClass(Locations::class);
        } catch (ReflectionException $e) {
            throw new UnexpectedFailure($cities, $e);
        }

        $constructor = $reflection->getConstructor();
        $constructor->setAccessible(true);
        /** @var Locations $location */
        $location = $reflection->newInstanceWithoutConstructor();
        $constructor->invokeArgs($location, $cities);

        return $location;
    }
}
