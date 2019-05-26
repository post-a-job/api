<?php

declare(strict_types=1);

namespace PostAJob\API\Job\HTTP\Middleware;

interface RequestMiddlewareInterface
{
    public const FAILURE_ATTRIBUTE_NAME = 'failed';
}
