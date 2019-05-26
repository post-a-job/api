<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Service\BuildLocation;

use PostAJob\API\Job\Service\BuildLocation\Exception\LocationsDoNotExist;
use PostAJob\API\Job\Service\BuildLocation\Exception\UnexpectedFailure;
use PostAJob\API\Job\ValueObject\Locations;

interface BuildLocation
{
    /**
     * @throws LocationsDoNotExist
     * @throws UnexpectedFailure
     */
    public function __invoke(array $cities): Locations;
}
