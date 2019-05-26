<?php

declare(strict_types=1);

namespace PostAJob\API\Trace\Container;

use PostAJob\API\Trace\HTTP\Middleware\Action\Trace as TracingAction;

final class RetrieveEntries
{
    public function __invoke(): array
    {
        $entries = [];

        $entries[TracingAction::class] = static function (): TracingAction {
            return new TracingAction();
        };

        return $entries;
    }
}
