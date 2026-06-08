<?php

namespace Database\Seeders;

use App\Models\InclusiveApplication;
use App\Models\Store;
use Illuminate\Database\Seeder;

class InclusiveApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::inRandomOrder()->take(3)->get();

        if ($stores->isEmpty()) {
            return;
        }

        foreach ($stores as $store) {
            InclusiveApplication::factory()->create([
                'store_id' => $store->id,
                'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            ]);
        }
    }
}
