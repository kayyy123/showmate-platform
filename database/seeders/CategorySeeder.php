<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'icon' => 'bi-phone'],
            ['name' => 'Fashion Pria', 'icon' => 'bi-person'],
            ['name' => 'Fashion Wanita', 'icon' => 'bi-gender-female'],
            ['name' => 'Makanan & Minuman', 'icon' => 'bi-cup-hot'],
            ['name' => 'Kesehatan & Kecantikan', 'icon' => 'bi-heart-pulse'],
            ['name' => 'Olahraga & Outdoor', 'icon' => 'bi-bicycle'],
            ['name' => 'Otomotif', 'icon' => 'bi-car-front'],
            ['name' => 'Buku & Alat Tulis', 'icon' => 'bi-book'],
            ['name' => 'Perlengkapan Rumah', 'icon' => 'bi-house-door'],
            ['name' => 'Mainan & Hobi', 'icon' => 'bi-dice-6'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => "Kategori {$cat['name']}",
                    'icon' => $cat['icon'],
                    'is_active' => true,
                ]
            );
        }
    }
}
