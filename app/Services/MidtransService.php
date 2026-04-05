<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Transaction;

class MidtransService
{
    public function __construct()
    {
        // Konfigurasi menggunakan package Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function getSnapToken(Transaction $transaction)
    {
        // Format item untuk Midtrans
        $itemDetails = [];
        foreach ($transaction->transactionItems as $item) {
            $itemDetails[] = [
                'id' => $item->product_id,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'name' => mb_substr($item->product->name, 0, 50), // Midtrans membatasi panjang nama item
            ];
        }

        // Tambahkan jasa dokter/tindakan jika ada
        if ($transaction->jasa_dokter > 0) {
            $itemDetails[] = [
                'id' => 'JASA-DOKTER',
                'price' => $transaction->jasa_dokter,
                'quantity' => 1,
                'name' => 'Jasa Dokter'
            ];
        }

        if ($transaction->jasa_tindakan > 0) {
            $itemDetails[] = [
                'id' => 'JASA-TINDAKAN',
                'price' => $transaction->jasa_tindakan,
                'quantity' => 1,
                'name' => 'Jasa Tindakan'
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->transaction_number,
                'gross_amount' => $transaction->total,
            ],
            'customer_details' => [
                'first_name' => $transaction->name,
                // Bisa tambahkan email atau nomor HP jika ada di table users/customer
            ],
            'item_details' => $itemDetails,
        ];

        return Snap::getSnapToken($params);
    }
}
