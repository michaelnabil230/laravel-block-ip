<?php

namespace MichaelNabil230\BlockIp;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MichaelNabil230\BlockIp\Commands\BlockIpCommand;

class BlockIpServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-block-ip')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-block-ip_table')
            ->hasCommand(BlockIpCommand::class);
    }
}
