<?php

declare(strict_types=1);

namespace PostAJob\API\Probe\HTTP\Middleware;

interface RequestMiddlewareInterface
{
    public const API_ATTRIBUTE_NAME = 'api';
    public const CHECK_ATTRIBUTE_NAME = 'checks';
    public const FAILURE_ATTRIBUTE_NAME = 'failed';
}
