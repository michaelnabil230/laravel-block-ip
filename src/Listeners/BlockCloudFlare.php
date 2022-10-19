<?php

namespace MichaelNabil230\BlockIp\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use MichaelNabil230\BlockIp\Events\BlockIpCreated;
use MichaelNabil230\BlockIp\Services\CloudFlare;

class BlockCloudFlare implements ShouldQueue
{
    public function handle(BlockIpCreated $event): void
    {
        if (! config('block-ip.webhook_cloud_flare.enable', false)) {
            return;
        }

        $cloudflareId = CloudFlare::block($event->blockIp->id);

        $event->blockIp->update(['cloudflare_id' => $cloudflareId]);
    }
}
