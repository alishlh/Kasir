<?php

namespace App\Filament\Resources\TransactionResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\TransactionItem;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Resources\RelationManagers\RelationManager;

class TransactionItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactionItems';

    protected static ?string $title = 'Transaction Items';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\ImageColumn::make('productWithTrashed.image')
                    ->label('Gambar'),
                Tables\Columns\TextColumn::make('productWithTrashed.name')
                    ->label('Nama Produk'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah'),
                Tables\Columns\TextColumn::make('cost_price')
                    ->label('Harga Modal')
                    ->prefix('Rp ')
                    ->numeric(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Jual')
                    ->prefix('Rp ')
                    ->numeric(),
                // Tambahkan kolom total harga
                Tables\Columns\TextColumn::make('total_profit')
                    ->label('Profit')
                    ->prefix('Rp ')
                    ->numeric()
                    ->summarize(
                        Sum::make()
                        ->label('Total profit')
                    ->prefix('Rp ')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

}
