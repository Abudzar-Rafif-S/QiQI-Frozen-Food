<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $discounts = [];

        // Generate 5, 10, 15, ... 100
        for ($i = 5; $i <= 100; $i += 5) {
            $discounts[] = [
                'value' => $i, // persen
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('discounts')->insert($discounts);
    }
}
