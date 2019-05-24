<?php
ini_set('display_errors', 'stderr');
use Moon\Container\Container;
use Moon\Moon\{
    AppFactory, Router
};
use Psr\Http\Message\ServerRequestInterface;
use Spiral\Goridge\StreamRelay;
use Spiral\RoadRunner\{
    PSR7Client, Worker
};

require __DIR__ . '/../vendor/autoload.php';
$env = new \Dotenv\Dotenv(__DIR__ . '/../');
$env->load();

$worker = new Worker(new StreamRelay(STDIN, STDOUT));
$psr7 = new PSR7Client($worker);
while ($req = $psr7->acceptRequest()) {
    try {
        $containerEntries = require __DIR__ . '/../bootstrap/containerEntries.php';
        $containerEntries[ServerRequestInterface::class] = $req;
        $container = new Container($containerEntries);
        $app = AppFactory::buildFromContainer($container);
        $router = new Router();
        $router->get('/[{name}]', function (ServerRequestInterface $request) {

            return "Hello {$request->getAttribute('name', 'World')}";
        });

        $psr7->respond($app->run($router->pipelines()));
    } catch (\Throwable $e) {
        $psr7->getWorker()->error((string)$e);
    }
}
