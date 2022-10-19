<?php

namespace MichaelNabil230\BlockIp;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use MichaelNabil230\BlockIp\Notifications\Channels\Discord\DiscordChannel;
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
            ->hasMigration('create_block_ip_table')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('michaelnabil230/laravel-block-ip');
            })
            ->hasCommands([
                Commands\BlockCommand::class,
                Commands\UnblockCommand::class,
            ]);
    }

    public function bootingPackage()
    {
        $this->app->singleton(BlockIpRegistrar::class);

        $this->bootEvents();
    }

    public function packageRegistered()
    {
        $this->registerDiscordChannel();
    }

    public function events(): array
    {
        return [
            Events\BlockIpCreated::class => [
                Listeners\BlockCloudFlare::class,
                Listeners\AddIpInCache::class,
                Listeners\SendEmailNotification::class,
            ],
            Events\DeletingBlockIp::class => [
                Listeners\UnblockCloudFlare::class,
            ],
            Events\BlockIpDeleted::class => [
                Listeners\ForgetCachedBlockIp::class,
            ],
        ];
    }

    protected function bootEvents(): void
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    protected function registerDiscordChannel()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('discord', fn ($app) => new DiscordChannel());
        });
    }
}
