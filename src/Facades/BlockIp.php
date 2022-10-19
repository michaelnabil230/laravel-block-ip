<?php

namespace MichaelNabil230\BlockIp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MichaelNabil230\BlockIp\BlockIp
 */
class BlockIp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \MichaelNabil230\BlockIp\BlockIp::class;
    }
}
