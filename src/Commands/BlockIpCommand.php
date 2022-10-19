<?php

namespace MichaelNabil230\BlockIp\Commands;

use Illuminate\Console\Command;

class BlockIpCommand extends Command
{
    public $signature = 'laravel-block-ip';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
