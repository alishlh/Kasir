<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Actions;
use App\Models\Product;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\TransactionResource;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

     protected function beforeSave(): void
{
    $invalidProducts = collect($this->form->getState()['transactionItems'] ?? [])
        ->filter(function ($item) {
            $product = Product::withTrashed()->find($item['product_id']);
            return $product?->trashed();
        });

    if ($invalidProducts->isEmpty()) {
        Notification::make()
            ->title('Produk tidak tersedia')
            ->body('Ada produk yang sudah dihapus. Edit tidak dapat dilanjutkan.')
            ->danger()
            ->send();

        $this->halt(); // menghentikan proses save
    }
}


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
