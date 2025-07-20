<?php

namespace App\Helpers;

use App\Models\Transaction;

class TransactionHelper
{
    public static function generateUniqueTrxId(): string
    {
        $prefix = 'TRX';
        do {
            $randomString = $prefix . mt_rand(10000000, 99999999); 
        } while (Transaction::where('transaction_number', $randomString)->exists());

        return $randomString;
    }
}
