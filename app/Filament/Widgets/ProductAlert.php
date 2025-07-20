<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Tables;
use App\Models\Product;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\TableWidget as BaseWidget;

class ProductAlert extends BaseWidget
{
    protected static ?int $sort = 4;
    protected static ?string $heading = 'Status Stok Produk';

  
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()->where('stock', '<', 10)->orderBy('stock', 'asc')
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                ->label('Gambar')
                ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('stock_status')
                    ->label('Status')
                    ->getStateUsing(static function ($record): string {
                        if ($record->stock <= 0) {
                            return 'Habis';
                        } elseif ($record->stock < 10) {
                            return 'Hampir Habis';
                        }
                        return 'Aman';
                    })
                    ->color(static function ($state): string {
                        return match ($state) {
                            'Habis' => 'danger',
                            'Hampir Habis' => 'warning',
                            'Aman' => 'success',
                            default => 'secondary',
                        };
                    }),
                Tables\Columns\BadgeColumn::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->color(static function ($state): string {
                        if ($state <= 0) {
                            return 'danger';
                        } elseif ($state < 10) {
                            return 'warning';
                        }
                        return 'success';
                    })
                    ->sortable(),
                
            ])
            ->defaultPaginationPageOption(5);
    }
}
