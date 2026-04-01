<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();

        foreach ($stores as $store) {
            PaymentMethod::create([
                'store_id' => $store->id,
                'name' => 'Cash',
                'image' => null,
                'is_cash' => true,
            ]);
            PaymentMethod::create([
                'store_id' => $store->id,
                'name' => 'Transfer Bank',
                'image' => null,
                'is_cash' => false,
            ]);
        }
    }
}
