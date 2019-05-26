<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Moon\Container\Container;
use Moon\Moon\AppFactory;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\Goridge\StreamRelay;
use Spiral\RoadRunner\PSR7Client;
use Spiral\RoadRunner\Worker;

require __DIR__.'/../vendor/autoload.php';
$env = new Dotenv(__DIR__.'/../');
$env->load();

$worker = new Worker(new StreamRelay(STDIN, STDOUT));
$psr7 = new PSR7Client($worker);
while ($req = $psr7->acceptRequest()) {
    try {
        $entries = require __DIR__.'/../bootstrap/entries.php';
        $entries[ServerRequestInterface::class] = $req;
        $container = new Container($entries);
        $pipelines = require __DIR__.'/../bootstrap/pipelines.php';
        $app = AppFactory::buildFromContainer($container);
        $psr7->respond($app->run($pipelines));
    } catch (Throwable $e) {
        $psr7->getWorker()->error((string) $e);
    }
}
