<?php

namespace MichaelNabil230\BlockIp\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use MichaelNabil230\BlockIp\Models\BlockIp;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'block-ip:unblock')]
class UnblockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'block-ip:unblock 
                                        {--all : Unblock all Ips from database}
                                        {--ips : Unblock IPs in the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unblock all ips form database';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->option('all')) {
            $this->info($this->unblockAll().' entries unblock.');

            return;
        }

        if ($this->option('ips')) {
            $this->info($this->unblockIps().' entries unblock.');

            return;
        }
    }

    /**
     * Unblock all of the entries older than the given date.
     *
     * @return int
     */
    public function unblockAll(): int
    {
        $blockIps = BlockIp::get('id');

        foreach ($blockIps as $blockIp) {
            $blockIp->delete();
        }

        return $blockIps->count();
    }

    /**
     * Unblock IPs into the database.
     *
     * @return int
     */
    private function unblockIps(): int
    {
        $ips = Arr::wrap(explode(',', $this->option('ips') ?? []));

        $blockIps = BlockIp::whereIn('ip_address', $ips)->get('id');

        foreach ($blockIps as $blockIp) {
            $blockIp->delete();
        }

        return $blockIps->count();
    }
}
