<?php

namespace MichaelNabil230\BlockIp\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use MichaelNabil230\BlockIp\BlockIpServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'MichaelNabil230\\BlockIp\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            BlockIpServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-block-ip_table.php.stub';
        $migration->up();
        */
    }
}
