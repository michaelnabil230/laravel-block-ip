<?php

namespace MichaelNabil230\BlockIp\Listeners;

use MichaelNabil230\BlockIp\BlockIpRegistrar;
use MichaelNabil230\BlockIp\Events\BlockIpDeleted;

class ForgetCachedBlockIp
{
    public function handle(BlockIpDeleted $event): void
    {
        app(BlockIpRegistrar::class)->forgetCachedBlockIp($event->blockIp->ip_address);
    }
}
