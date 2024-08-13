<?php

declare(strict_types=1);

namespace Tests;

use AllowDynamicProperties;
use Exception;
use PHPUnit\Runner\Extension\Extension as ExtensionInterface;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

#[AllowDynamicProperties]
class DatabaseMigrationExtension implements ExtensionInterface
{
    public function __construct()
    {
        $kernel = require __DIR__ . '/bootstrap.php';
        $this->application = new Application($kernel);
    }

    /**
     * @throws Exception
     */
    final public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $this->application->setAutoExit(false);
        $this->runMigrations();
    }

    /**
     * @throws Exception
     */
    private function runMigrations(): void
    {
        $input = new ArrayInput(['command' => 'doctrine:migrations:migrate', '--no-interaction' => true]);
        $this->application->run($input, new NullOutput());
    }
}
