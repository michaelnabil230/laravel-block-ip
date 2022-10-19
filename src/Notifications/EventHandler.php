<?php

namespace MichaelNabil230\BlockIp\Notifications;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Notification;
use MichaelNabil230\BlockIp\Events;
use MichaelNabil230\BlockIp\Exceptions\NotificationCouldNotBeSent;

class EventHandler
{
    public function __construct(
        protected Repository $config
    ) {
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen([Events\BlockIpCreated::class, Events\BlockIpSaved::class], function ($event) {
            $notifiable = $this->determineNotifiable();

            $notification = $this->determineNotification($event);

            $notifiable->notify($notification);
        });
    }

    protected function determineNotifiable()
    {
        $notifiableClass = $this->config->get('block-ip.notifications.notifiable');

        return app($notifiableClass);
    }

    protected function determineNotification($event): Notification
    {
        $lookingForNotificationClass = class_basename($event).'Notification';

        $notificationClass = collect($this->config->get('block-ip.notifications.notifications'))
            ->keys()
            ->first(fn (string $notificationClass) => class_basename($notificationClass) === $lookingForNotificationClass);

        if (! $notificationClass) {
            throw NotificationCouldNotBeSent::noNotificationClassForEvent($event);
        }

        return new $notificationClass($event);
    }
}
