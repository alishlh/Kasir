<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\TransactionItem;

class TransactionItemObserver
{
/**
     * Handle the TransactionItem "created" event.
     */
    public function created(TransactionItem $TransactionItem): void
    {
        $product = Product::find($TransactionItem->product_id);
        $product->decrement('stock', $TransactionItem->quantity);
    }

    /**
     * Handle the TransactionItem "updated" event.
     */
    public function updated(TransactionItem $TransactionItem): void
    {
        $product = Product::find($TransactionItem->product_id);
        $originalQuantity = $TransactionItem->getOriginal('quantity');
        $newQuantity = $TransactionItem->quantity;

        if ($originalQuantity !=  $newQuantity) {
            $product->increment('stock', $originalQuantity);
            $product->decrement('stock', $newQuantity);
        }

    }

    /**
     * Handle the TransactionItem "deleted" event.
     */
    public function deleted(TransactionItem $TransactionItem): void
    {
        $product = Product::find($TransactionItem->product_id);
        $product->increment('stock', $TransactionItem->quantity);
    }

 
}
