<?php

namespace MichaelNabil230\BlockIp\Notifications;

use Illuminate\Notifications\Notifiable as NotifiableTrait;

class Notifiable
{
    use NotifiableTrait;

    public function routeNotificationForMail(): string | array
    {
        return config('block-ip.notifications.mail.to');
    }

    public function routeNotificationForSlack(): string
    {
        return config('block-ip.notifications.slack.webhook_url');
    }

    public function routeNotificationForDiscord(): string
    {
        return config('block-ip.notifications.discord.webhook_url');
    }
}
