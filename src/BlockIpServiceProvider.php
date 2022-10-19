<?php

namespace MichaelNabil230\BlockIp;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use MichaelNabil230\BlockIp\Commands\UnlockCommand;
use MichaelNabil230\BlockIp\Notifications\Channels\Discord\DiscordChannel;
use MichaelNabil230\BlockIp\Notifications\EventHandler;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BlockIpServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-block-ip')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigration('create_laravel-block-ip_table')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('michaelnabil230/laravel-block-ip');
            })
            ->hasCommand(UnlockCommand::class);
    }

    public function packageRegistered()
    {
        $this->app['events']->subscribe(EventHandler::class);

        $this->registerDiscordChannel();
    }

    protected function registerDiscordChannel()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('discord', fn ($app) => new DiscordChannel());
        });
    }
}
