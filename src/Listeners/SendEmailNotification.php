<?php

namespace MichaelNabil230\BlockIp\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use MichaelNabil230\BlockIp\Events\BlockIpCreated;
use MichaelNabil230\BlockIp\Notifications\Notifications\BlockIpNotification;

class SendEmailNotification implements ShouldQueue
{
    public function handle(BlockIpCreated $event): void
    {
        $notifiable = $this->determineNotifiable();

        $notification = new BlockIpNotification($event);

        $notifiable->notify($notification);
    }

    protected function determineNotifiable()
    {
        $notifiableClass = config('block-ip.notifications.notifiable');

        return app($notifiableClass);
    }
}
