<?php

namespace MichaelNabil230\BlockIp\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

abstract class BaseNotification extends Notification
{
    public function via(): array
    {
        $notificationChannels = config('block-ip.notifications.channels', ['mail']);

        return array_filter($notificationChannels);
    }

    public function applicationName(): string
    {
        $name = config('app.name') ?? config('app.url') ?? 'Laravel Block Ip';
        $env = app()->environment();

        return "{$name} ({$env})";
    }

    protected function blockIpDestinationProperties(): Collection
    {
        $blockIp = $this->event->blockIp;

        $applicationName = trans('block-ip::notifications.application_name');
        $ip = trans('block-ip::notifications.ip');
        $blockedInCloudFlare = trans('block-ip::notifications.blocked_in_cloud_flare');

        $id = trans('block-ip::notifications.user.id');
        $name = trans('block-ip::notifications.user.name');
        $email = trans('block-ip::notifications.user.email');

        return collect([
            $applicationName => $this->applicationName(),
            $ip => $blockIp->ip_address,
            $blockedInCloudFlare => ! is_null($blockIp->cloudflare_id),
            $id => $blockIp->user?->id,
            $name => $blockIp->user?->name,
            $email => $blockIp->user?->email,
        ])->filter();
    }
}
