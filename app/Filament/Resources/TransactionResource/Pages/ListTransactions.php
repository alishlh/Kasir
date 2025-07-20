<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Models\Transaction;
use Filament\Actions;
use App\Models\Setting;
use App\Models\TransactionItem;
use App\Filament\Resources\TransactionResource;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function printStruk($order,$items)
    {
        $this->dispatch('doPrintReceipt', 
            store: Setting::first(),
            order: $order,
            items: $items,
            date: $order->created_at->format('d-m-Y H:i:s')
        );

    }

}
