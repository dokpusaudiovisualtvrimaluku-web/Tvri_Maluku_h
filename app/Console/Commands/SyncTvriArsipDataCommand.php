<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TvriArsipDataSyncService;

class SyncTvriArsipDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tvri:sync-arsip-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi seluruh data operasional TVRI ke Arsip Data (SimandatDocument)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi data operasional TVRI ke Arsip Data...');

        $count = TvriArsipDataSyncService::syncAllExistingData();

        $this->newLine();
        $this->info("Sinkronisasi selesai! Total {$count} record data operasional TVRI berhasil disinkronkan ke Arsip Data.");
        return 0;
    }
}
