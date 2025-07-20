<?php

namespace App\Observers;

use App\Models\InventoryItem;
use Illuminate\Support\Facades\Log;

class InventoryItemObserver
{
    public function created(InventoryItem $item)
    {
        $type = $item->inventory->type;
        $source = $item->inventory->source;

        if ($type === 'in') {
            // Tambah stok
            $item->product->increment('stock', $item->quantity);
        } elseif ($type === 'out') {
            // Kurangi stok
            $item->product->decrement('stock', $item->quantity);
        } elseif ($type === 'adjustment') {
            // Penyesuaian langsung (stock opname)
            $item->product->update(['stock' => $item->quantity]);
        }
    }

    public function updated(InventoryItem $item)
    {
        $type = $item->inventory->type;
        $originalQty = $item->getOriginal('quantity');
        $newQty = $item->quantity;

        $product = $item->product;

        if ($type === 'in') {
            $product->increment('stock', $newQty - $originalQty);
        } elseif ($type === 'out') {
            $product->decrement('stock', $newQty - $originalQty);
        } elseif ($type === 'adjustment') {
            $product->update(['stock' => $newQty]);
        }
    }

    public function deleted(InventoryItem $item)
    {
        $type = $item->inventory->type;

        if ($type === 'in') {
            $item->product->decrement('stock', $item->quantity);
        } elseif ($type === 'out') {
            $item->product->increment('stock', $item->quantity);
        } elseif ($type === 'adjustment') {
            // Tidak bisa dikembalikan otomatis karena tidak tahu stok sebelumnya
            // Bisa log saja
            Log::warning("Item adjustment dihapus: Tidak dapat mengembalikan stok secara akurat.");
        }
    }

   
}
