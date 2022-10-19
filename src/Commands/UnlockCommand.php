<?php

namespace MichaelNabil230\BlockIp\Commands;

use Illuminate\Console\Command;
use MichaelNabil230\BlockIp\Models\BlockIp;

class UnlockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'block-ip:unlock 
                                        {--chunkSize=1000 : The number of entries that will be inserted at once into the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlock all ips form database';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info($this->unlock().' entries unlock.');
    }

    /**
     * Unlock all of the entries older than the given date.
     *
     * @return int
     */
    public function unlock(): int
    {
        $chunkSize = $this->option('chunkSize') ?? 1000;

        $query = BlockIp::query();

        $totalDeleted = 0;

        do {
            $deleted = $query->take($chunkSize)->delete();

            $totalDeleted += $deleted;
        } while ($deleted !== 0);

        return $totalDeleted;
    }
}
