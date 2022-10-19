<?php

namespace MichaelNabil230\BlockIp\Notifications\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use MichaelNabil230\BlockIp\Events\BlockIpCreated;
use MichaelNabil230\BlockIp\Notifications\BaseNotification;
use MichaelNabil230\BlockIp\Notifications\Channels\Discord\DiscordMessage;

class BlockIpNotification extends BaseNotification
{
    public function __construct(public BlockIpCreated $event)
    {
    }

    public function toMail(): MailMessage
    {
        $mailMessage = (new MailMessage())
            ->from(config('block-ip.notifications.mail.from.address', config('mail.from.address')), config('block-ip.notifications.mail.from.name', config('mail.from.name')))
            ->subject(trans('block-ip::notifications.has_the_new_ip_been_blocked', ['application_name' => $this->applicationName()]))
            ->line(trans('block-ip::notifications.has_the_new_ip_been_blocked', ['application_name' => $this->applicationName()]));

        $this->blockIpDestinationProperties()->each(function ($value, $name) use ($mailMessage) {
            if (!is_null($value)) {
                $mailMessage->line("{$name}: $value");
            }
        });

        return $mailMessage;
    }

    public function toSlack(): SlackMessage
    {
        return (new SlackMessage())
            ->success()
            ->from(config('block-ip.notifications.slack.username'), config('block-ip.notifications.slack.icon'))
            ->to(config('block-ip.notifications.slack.channel'))
            ->content(trans('block-ip::notifications.has_the_new_ip_been_blocked'))
            ->attachment(function (SlackAttachment $attachment) {
                $attachment->fields($this->blockIpDestinationProperties()->toArray());
            });
    }

    public function toDiscord(): DiscordMessage
    {
        return (new DiscordMessage())
            ->success()
            ->from(config('block-ip.notifications.discord.username'), config('block-ip.notifications.discord.avatar_url'))
            ->title(trans('block-ip::notifications.has_the_new_ip_been_blocked'))
            ->fields($this->blockIpDestinationProperties()->toArray());
    }
}
