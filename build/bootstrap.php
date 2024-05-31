<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (!class_exists(Dotenv::class)) {
    throw new LogicException('Please run "composer require symfony/dotenv" to load the ".env" files configuring the application.');
}

(new Dotenv())->loadEnv(dirname(__DIR__).'/.env');

$_SERVER += $_ENV;
$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null) ?: 'dev';
$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? 'prod' !== $_SERVER['APP_ENV'];
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = (int) $_SERVER['APP_DEBUG'] || filter_var(
    $_SERVER['APP_DEBUG'],
    FILTER_VALIDATE_BOOLEAN
) ? '1' : '0';

// Prepare the test environment
// passthru(sprintf('APP_ENV=%s php "%s/../bin/console" doctrine:database:drop --force --if-exists', $_ENV['APP_ENV'], __DIR__));
// passthru(sprintf('APP_ENV=%s php "%s/../bin/console" doctrine:database:create', $_ENV['APP_ENV'], __DIR__));
// passthru(sprintf('APP_ENV=%s php "%s/../bin/console" doctrine:schema:update --complete --force', $_ENV['APP_ENV'], __DIR__));
// passthru(sprintf('APP_ENV=%s php "%s/../bin/console" doctrine:fixtures:load --no-interaction', $_ENV['APP_ENV'], __DIR__));
// passthru(sprintf('APP_ENV=%s php "%s/../bin/console" cache:clear --no-warmup', $_ENV['APP_ENV'], __DIR__));
