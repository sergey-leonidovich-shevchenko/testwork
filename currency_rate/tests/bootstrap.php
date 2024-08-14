<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require \dirname(__DIR__) . '/vendor/autoload.php';

// Load the .env.test file if it exists
if (\file_exists(\dirname(__DIR__) . '/.env.test')) {
    (new Dotenv())->load(\dirname(__DIR__) . '/.env.test');
}

// Set default environment variables for testing
$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = 'test';
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = '1';

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

return $kernel;
