<?php

namespace MichaelNabil230\BlockIp\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

abstract class BaseNotification extends Notification
{
    public function via(): array
    {
        $notificationChannels = config('block-ip.notifications.notifications.'.static::class);

        return array_filter($notificationChannels);
    }

    public function applicationName(): string
    {
        $name = config('app.name') ?? config('app.url') ?? 'Laravel';
        $env = app()->environment();

        return "{$name} ({$env})";
    }

    protected function backupDestinationProperties(): Collection
    {
        $applicationName = trans('block-ip::notifications.application_name');
        $ip = trans('block-ip::notifications.ip');
        $user = trans('block-ip::notifications.user');

        return collect([
            $applicationName => $this->applicationName(),
            $ip => '',
            $user => '',
        ])->filter();
    }
}
