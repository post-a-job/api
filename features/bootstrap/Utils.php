<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;

/**
 * Defines application features from the specific context.
 */
class Utils implements Context
{
    /**
     * @var Connection
     */
    private static $connection;

    /**
     * @BeforeSuite
     */
    public static function prepare(): void
    {
        $env = new Dotenv(__DIR__.'/../');
        $env->load();

        self::$connection = DriverManager::getConnection([
            'url' => \getenv('DATABASE_URL'),
            'driver' => 'pdo_pgsql',
        ]);
    }

    public static function getConnection(): Connection
    {
        return self::$connection;
    }
}
