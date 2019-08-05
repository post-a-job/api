<?php

declare(strict_types=1);

namespace PostAJob\API\Job\UseCase\SeeJob;

use PostAJob\API\Job\ValueObject\ID;

final class Command
{
    /**
     * @var ID
     */
    private $ID;

    public function __construct(ID $ID)
    {
        $this->ID = $ID;
    }

    public function ID(): ID
    {
        return $this->ID;
    }
}
