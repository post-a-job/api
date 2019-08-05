<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware\Map\Exception;

use RuntimeException;
use Throwable;

final class AttributeIsNotJob extends RuntimeException
{
    private const ERROR_FORMAT = 'The attribute is not a Job. Given: %s';

    public function __construct(string $classname, Throwable $previous = null)
    {
        parent::__construct(
            \sprintf(self::ERROR_FORMAT, $classname),
            $code = 0,
            $previous
        );
    }
}
