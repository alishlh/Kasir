<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();
        
        foreach ($stores as $store) {
            $products = Product::where('store_id', $store->id)->get();
            $paymentMethods = PaymentMethod::where('store_id', $store->id)->get();
            $users = $store->users;
            
            if ($products->isEmpty() || $paymentMethods->isEmpty() || $users->isEmpty()) {
                continue;
            }

            // Buat transaksi untuk 30 hari terakhir
            for ($i = 0; $i < 30; $i++) {
                $date = Carbon::now()->subDays($i);
                
                // Jumlah transaksi per hari (acak 3-8 transaksi)
                $dailyTransactionCount = rand(3, 8);
                
                for ($j = 0; $j < $dailyTransactionCount; $j++) {
                    $this->createStoreTransaction($store, $products, $paymentMethods, $users, $date);
                }
            }
        }
    }

    private function createStoreTransaction($store, $products, $paymentMethods, $users, $date)
    {
        $user = $users->random();
        $paymentMethod = $paymentMethods->random();
        $transactionDate = $date->copy()->setHour(rand(8, 21))->setMinute(rand(0, 59));
        
        $transaction = Transaction::create([
            'store_id' => $store->id,
            'user_id' => $user->id,
            'payment_method_id' => $paymentMethod->id,
            'transaction_number' => 'TRX-' . $store->id . '-' . strtoupper(bin2hex(random_bytes(4))),
            'name' => 'Customer ' . rand(1, 100),
            'total' => 0,
            'cash_received' => 0,
            'change' => 0,
            'is_bpjs' => rand(0, 10) > 8, // 20% chance of BPJS
            'created_at' => $transactionDate,
            'updated_at' => $transactionDate,
        ]);

        $total = 0;
        $itemCount = rand(1, 4);
        $selectedProducts = $products->random(min($itemCount, $products->count()));

        foreach ($selectedProducts as $product) {
            $qty = rand(1, 3);
            $price = $product->price;
            $costPrice = $product->cost_price;
            $subtotal = $price * $qty;
            $profit = ($price - $costPrice) * $qty;

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $qty,
                'price' => $price,
                'cost_price' => $costPrice,
                'total_profit' => $profit,
                'created_at' => $transactionDate,
                'updated_at' => $transactionDate,
            ]);

            $total += $subtotal;
        }

        // Update transaction total and cash received
        $cashReceived = ceil($total / 5000) * 5000;
        if (rand(0, 1)) $cashReceived += 10000; // random extra cash

        $transaction->update([
            'total' => $total,
            'cash_received' => $cashReceived,
            'change' => $cashReceived - $total,
        ]);
    }
}
