<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $now = Carbon::now();

        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'Obat Bebas (OTC) & Ringan',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 2,
                'name' => 'Obat Keras & Resep',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 3,
                'name' => 'Vitamin & Suplemen',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 4,
                'name' => 'Alat Kesehatan',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 5,
                'name' => 'Ibu & Anak',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 6,
                'name' => 'Perawatan Tubuh (Personal Care)',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

    }
}
