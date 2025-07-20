<?php

namespace App\Observers;

use App\Models\CashFlow;
use App\Models\Transaction;
use App\Helpers\TransactionHelper;

class TransactionObserver
{
    public function creating(Transaction $transaction)
    {
        $transaction->transaction_number = TransactionHelper::generateUniqueTrxId();
    }
    
    public function created(Transaction $transaction)
    {
        CashFlow::create([
            'date'   => now(),
            'type'   => 'income',
            'source' => 'sales',
            'amount' => $transaction->total,
            'notes'  => 'Pemasukan dari transaksi #' . $transaction->transaction_number,
        ]);
    }

    public function updated(Transaction $transaction)
    {
        // Misalnya jika total diupdate maka perbarui juga di CashFlow
        if ($transaction->isDirty('total')) {
            CashFlow::where('notes', 'like', "%Pemasukan dari transaksi #{$transaction->transaction_number}%")
                ->update([
                    'amount' => $transaction->total,
                ]);
        }
    }

    public function deleted(Transaction $transaction)
    {
        CashFlow::create([
            'date'   => now(),
            'type'   => 'expense',
            'source' => 'refund',
            'amount' => $transaction->total,
            'notes'  => 'Pembatalan transaksi #' . $transaction->transaction_number,
        ]);

        // Kembalikan stok produk
        foreach ($transaction->transactionItems as $item) {
            $product = $item->product;
            $product->stock += $item->quantity;
            $product->save();
        }
    }

    public function restored(Transaction $transaction)
    {
        CashFlow::create([
            'date'   => now(),
            'type'   => 'income',
            'source' => 'restored_sales',
            'amount' => $transaction->total,
            'notes'  => 'Restore transaksi #' . $transaction->transaction_number,
        ]);

        // Kurangi lagi stok
        foreach ($transaction->transactionItems()->get() as $item) {
            $product = $item->product;
            $product->stock -= $item->quantity;
            $product->save();
        }
    }

    
    public function forceDeleting(Transaction $transaction)
    {
        if(!$transaction->trashed()){
            foreach ($transaction->transactionItems()->get() as $item) {
                $product = $item->product;
                $product->stock += $item->quantity;
                $product->save();
            }
        }

    }

    public function forceDeleted(Transaction $transaction)
    {
        // Misalnya hapus CashFlow terkait
        CashFlow::where('notes', 'like', "%transaksi #{$transaction->transaction_number}%")->delete();

    }
}
