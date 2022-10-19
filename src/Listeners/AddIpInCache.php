<?php

namespace MichaelNabil230\BlockIp\Listeners;

use MichaelNabil230\BlockIp\BlockIpRegistrar;
use MichaelNabil230\BlockIp\Events\BlockIpCreated;

class AddIpInCache
{
    public function handle(BlockIpCreated $event): void
    {
        app(BlockIpRegistrar::class)->addCachedBlockIp($event->blockIp->ip_address);
    }
}
