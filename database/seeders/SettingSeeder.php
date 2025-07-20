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
        // Masukkan satu baris data pengaturan default
        DB::table('settings')->insert([
            'id' => 1,
            'logo' => null, // atau 'path/to/your/logo.png' jika ada
            'name' => 'Toko Maju Jaya',
            'phone' => '081234567890',
            'address' => 'Jl. Pahlawan No. 123, Kota Sejahtera, 14045',
            'print_via_bluetooth' => 1, // 0 = false, 1 = true
            'name_printer_local' => null, // contoh: 'EPSON-TM-T82'
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}