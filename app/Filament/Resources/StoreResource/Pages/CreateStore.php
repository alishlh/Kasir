<?php

namespace App\Filament\Resources\StoreResource\Pages;

use App\Filament\Resources\StoreResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStore extends CreateRecord
{
    protected static string $resource = StoreResource::class;

    protected function afterCreate(): void
    {
        // Otomatis attach user pembuat ke toko baru via pivot table
        // Agar toko baru muncul di tenant switcher milik user tersebut
        $this->record->users()->attach(auth()->id());
    }
}
