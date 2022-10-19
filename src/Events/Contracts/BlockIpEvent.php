<?php

namespace MichaelNabil230\BlockIp\Events\Contracts;

use Illuminate\Queue\SerializesModels;
use MichaelNabil230\BlockIp\Models\BlockIp;

abstract class BlockIpEvent
{
    use SerializesModels;

    public function __construct(public BlockIp $blockIp)
    {
    }
}
