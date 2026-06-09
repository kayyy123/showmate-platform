<?php

namespace App\Console\Commands;

use App\Models\Checkout;
use Illuminate\Console\Command;

class BackfillOrderCodes extends Command
{
    protected $signature = 'app:backfill-order-codes {--refresh : Regenerate all existing order codes to new format}';

    protected $description = 'Generate order_code for existing checkouts that do not have one';

    public function handle()
    {
        if ($this->option('refresh')) {
            return $this->refreshAll();
        }

        $checkouts = Checkout::whereNull('order_code')->get();

        if ($checkouts->isEmpty()) {
            $this->info('Semua checkout sudah memiliki ID pesanan.');
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($checkouts->count());
        $bar->start();

        foreach ($checkouts as $checkout) {
            $checkout->order_code = Checkout::generateUniqueOrderCode();
            $checkout->saveQuietly();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Berhasil mengisi {$checkouts->count()} ID pesanan.");

        return Command::SUCCESS;
    }

    private function refreshAll(): int
    {
        $checkouts = Checkout::all();

        if ($checkouts->isEmpty()) {
            $this->info('Tidak ada checkout.');
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($checkouts->count());
        $bar->start();

        foreach ($checkouts as $checkout) {
            $checkout->order_code = Checkout::generateUniqueOrderCode();
            $checkout->saveQuietly();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Berhasil merefresh {$checkouts->count()} ID pesanan ke format baru (ELK-XXXXXXXXXX).");

        return Command::SUCCESS;
    }
}
