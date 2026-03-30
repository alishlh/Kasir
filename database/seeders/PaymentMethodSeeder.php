<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::create([
            'name' => 'Cash',
            'image' => null,
            'is_cash' => true,
        ]);
        PaymentMethod::create([
            'name' => 'Transfer Bank',
            'image' => null,
            'is_cash' => false,
        ]);
    }
}
