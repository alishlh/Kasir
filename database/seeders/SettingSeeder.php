<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            'id' => 1,
            'logo' => "products/product-default.jpg",
            'name' => 'Apotek Azzahra',
            'phone' => '081234567890',
            'address' => 'Jl. Pahlawan No. 123, Kota Sejahtera, 14045',
            'print_via_bluetooth' => 1,
            'name_printer_local' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
