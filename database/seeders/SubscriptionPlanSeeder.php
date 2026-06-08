<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'price' => 0,
                'duration_days' => 9999,
                'max_products' => 10,
                'max_stores' => 1,
                'features' => [
                    'Buat 1 toko',
                    'Upload 10 produk',
                    'Link katalog gratis',
                    'Statistik dasar',
                ],
            ],
            [
                'name' => 'Basic',
                'price' => 50000,
                'duration_days' => 30,
                'max_products' => 50,
                'max_stores' => 2,
                'features' => [
                    'Buat 2 toko',
                    'Upload 50 produk',
                    'Link katalog kustom',
                    'Statistik lengkap',
                    'Dukungan prioritas',
                ],
            ],
            [
                'name' => 'Premium',
                'price' => 150000,
                'duration_days' => 30,
                'max_products' => 9999,
                'max_stores' => 5,
                'features' => [
                    'Buat 5 toko',
                    'Produk tidak terbatas',
                    'Link katalog kustom',
                    'Statistik lengkap',
                    'Dukungan prioritas',
                    'Bebas logo',
                ],
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::firstOrCreate(
                ['slug' => Str::slug($plan['name'])],
                [
                    'name' => $plan['name'],
                    'description' => "Paket {$plan['name']} - Cocok untuk " . ($plan['price'] === 0 ? 'pemula' : ($plan['price'] < 100000 ? 'usaha kecil' : 'bisnis berkembang')),
                    'price' => $plan['price'],
                    'duration_days' => $plan['duration_days'],
                    'max_products' => $plan['max_products'],
                    'max_stores' => $plan['max_stores'],
                    'features' => json_encode($plan['features']),
                    'is_active' => true,
                ]
            );
        }
    }
}
