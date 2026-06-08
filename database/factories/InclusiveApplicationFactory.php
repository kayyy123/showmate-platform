<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

class InclusiveApplicationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'disability_type' => fake()->randomElement(['tunanetra', 'tunarungu', 'tunawicara', 'tunadaksa', 'lainnya']),
            'identity_document' => 'documents/ktp-' . fake()->uuid() . '.jpg',
            'support_document' => 'documents/surat-' . fake()->uuid() . '.jpg',
            'description' => fake()->sentence(),
            'status' => 'pending',
        ];
    }
}
