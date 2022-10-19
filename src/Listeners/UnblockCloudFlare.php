<?php

namespace MichaelNabil230\BlockIp\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use MichaelNabil230\BlockIp\Events\DeletingBlockIp;
use MichaelNabil230\BlockIp\Services\CloudFlare;

class UnblockCloudFlare implements ShouldQueue
{
    public function handle(DeletingBlockIp $event): void
    {
        if (! config('block-ip.webhook_cloud_flare.enable', false)) {
            return;
        }

        if (is_null($event->blockIp->cloudflare_id)) {
            return;
        }

        CloudFlare::unblock($event->blockIp->cloudflare_id);
    }
}
