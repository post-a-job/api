<?php

declare(strict_types=1);

namespace PostAJob\API\Job\Service\Map;

use PostAJob\API\Job\Job;

final class JobToArray
{
    private const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public function __invoke(Job $job): array
    {
        return [
            'id' => $job->id()->value(),
            'title' => $job->title()->value(),
            'description' => $job->description()->value(),
            'salary' => $job->salary()->toArray(),
            'company' => $job->company()->value(),
            'locations' => $job->location()->toArray(),
            'programming_languages' => $job->programmingLanguages()->toArray(),
            'posted_at' => $job->postedAt()->format(self::DATE_TIME_FORMAT),
            'last_update' => $job->lastUpdate() ? $job->lastUpdate()->format(self::DATE_TIME_FORMAT) : null,
        ];
    }
}
