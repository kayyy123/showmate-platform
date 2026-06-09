<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class BackfillProductCodes extends Command
{
    protected $signature = 'app:backfill-product-codes';

    protected $description = 'Generate product_code for existing products that do not have one';

    public function handle()
    {
        $products = Product::whereNull('product_code')->get();

        if ($products->isEmpty()) {
            $this->info('Semua produk sudah memiliki kode produk.');
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $product->product_code = Product::generateUniqueProductCode();
            $product->saveQuietly();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Berhasil mengisi {$products->count()} kode produk.");

        return Command::SUCCESS;
    }
}
