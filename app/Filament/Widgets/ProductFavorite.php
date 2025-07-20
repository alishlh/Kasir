<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Product;
use App\Models\OrderProduct;

class ProductFavorite extends BaseWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Produk Terlaris';
    public function table(Table $table): Table
    {
        $productQuery = Product::query()
            ->select('products.*') // Pastikan semua kolom dari products ikut diambil
            ->withCount('transactionItems')
            ->orderBy('transaction_items_count', 'desc')
            ->take(10);

        return $table
            ->query($productQuery)
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                ->label('Gambar')
                ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_items_count')
                    ->label('Jumlah Pembelian')
                    ->alignCenter(),
            ])
            ->defaultPaginationPageOption(5);
    }
}
