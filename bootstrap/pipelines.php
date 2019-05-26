<?php

declare(strict_types=1);

use Moon\Moon\Collection\MatchablePipelineArrayCollection;
use Moon\Moon\Router;
use PostAJob\API\Job\HTTP\BuildRouter as BuildJobRouter;
use PostAJob\API\Probe\HTTP\BuildRouter as BuildProbeRouter;

/** @var Router $jobs */
$jobs = $container->get(BuildJobRouter::class)();
/** @var Router $probe */
$probe = $container->get(BuildProbeRouter::class)();

$pipelines = new MatchablePipelineArrayCollection();
$pipelines->merge($probe->pipelines());
$pipelines->merge($jobs->pipelines());

return $pipelines;
