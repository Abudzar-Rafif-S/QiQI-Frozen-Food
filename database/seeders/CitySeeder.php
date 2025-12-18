<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            'Surabaya',
            'Sidoarjo',
            'Gresik',
            'Mojokerto',
            'Jombang',
            'Malang',
            'Batu',
            'Pasuruan',
            'Probolinggo',
            'Lumajang',
            'Bondowoso',
            'Situbondo',
            'Banyuwangi',
            'Jember',
            'Blitar',
            'Tulungagung',
            'Kediri',
            'Nganjuk',
            'Madiun',
            'Magetan',
            'Ngawi',
            'Bojonegoro',
            'Tuban',
            'Lamongan',
            'Pacitan',
            'Ponorogo',
            'Trenggalek',
        ];

        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'city_name' => $city,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
