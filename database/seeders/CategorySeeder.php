<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $stores = Store::all();

        $categories = [
            'Obat Bebas (OTC) & Ringan',
            'Obat Keras & Resep',
            'Vitamin & Suplemen',
            'Alat Kesehatan',
            'Ibu & Anak',
            'Perawatan Tubuh (Personal Care)',
        ];

        foreach ($stores as $store) {
            foreach ($categories as $category) {
                DB::table('categories')->insert([
                    'store_id' => $store->id,
                    'name' => $category,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
