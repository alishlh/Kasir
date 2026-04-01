<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();

        foreach ($stores as $store) {
            DB::table('settings')->insert([
                'store_id' => $store->id,
                'logo' => "products/product-default.jpg",
                'name' => $store->name,
                'phone' => $store->phone ?? '081234567890',
                'address' => $store->address ?? 'Jl. Pahlawan No. 123',
                'print_via_bluetooth' => 1,
                'name_printer_local' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
