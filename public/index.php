<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Moon\Container\Container;
use Moon\Moon\AppFactory;

require __DIR__.'/../vendor/autoload.php';
$env = new Dotenv(__DIR__.'/../');
$env->load();
$entries = require __DIR__.'/../bootstrap/entries.php';
$container = new Container($entries);
$pipelines = require __DIR__.'/../bootstrap/pipelines.php';
$app = AppFactory::buildFromContainer($container);
$app->sendResponse($app->run($pipelines));
