<?php

declare(strict_types=1);

namespace PostAJob\API\Job\ValueObject;

use PostAJob\API\Job\ValueObject\Exception\LocationIsEmpty;

class Locations
{
    /**
     * @var array
     */
    private $values;

    /**
     * @throws LocationIsEmpty
     */
    private function __construct(string ...$values)
    {
        foreach ($values as $key => $value) {
            $value = \trim($value);
            if ('' === $value) {
                throw new LocationIsEmpty();
            }
        }
        $this->values = $values;
    }

    public function toArray(): array
    {
        return $this->values;
    }

    public function equals(self $location): bool
    {
        return $this->values === $location->values;
    }

    private function __clone()
    {
    }
}
