<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Inventory;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Services\InventoryLabelService;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\InventoryResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class InventoryResource extends Resource implements HasShieldPermissions
{
    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'create',
            'update',
            'delete_any',
        ];
    }

    protected static ?string $model = Inventory::class;


    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $navigationLabel = 'Menejemen Inventori';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Menejemen Produk';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\ToggleButtons::make('type')
                            ->label('Tipe Stok')
                            ->options(InventoryLabelService::getTypes())
                            ->colors([
                                'in' => 'success',
                                'out' => 'danger',
                                'adjustment' => 'info',
                            ])
                            ->default('in')
                            ->grouped()
                            ->live(),
                        Forms\Components\Select::make('source')
                            ->label('Sumber')
                            ->required()
                            ->options(fn(Get $get) => InventoryLabelService::getSourceOptionsByType($get('type'))),
                        Forms\Components\TextInput::make('total')
                            ->label('Total Modal')
                            ->prefix('Rp ')
                            ->required()
                            ->numeric()
                            ->readOnly(),
                    ])->columns(3),
                Forms\Components\Section::make('Pemilihan Produk')->schema([
                    self::getItemsRepeater(),
                ]),
                Forms\Components\Section::make('Catatan')->schema([
                    Forms\Components\Textarea::make('notes')
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')
                ->label('No.Referensi')
                ->weight('semibold')
                ->copyable(),
                Tables\Columns\TextColumn::make('type')
                ->label('Tipe')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in' => 'Masuk',
                        'out' => 'Keluar',
                        'adjustment' => 'Penyesuaian',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'in' => 'heroicon-o-arrow-down-circle',
                        'out' => 'heroicon-o-arrow-up-circle',
                        'adjustment' => 'heroicon-o-arrow-path-rounded-square',

                    })
                    ->color(fn (string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        'adjustment' => 'info',
                    }),
                Tables\Columns\TextColumn::make('source')
                ->label('Sumber')
                ->formatStateUsing(fn($state, $record) => InventoryLabelService::getSourceLabel($record->type,$state)),
                Tables\Columns\TextColumn::make('total')
                ->numeric()
                ->prefix('Rp '),
                Tables\Columns\TextColumn::make('notes')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('inventoryItems')
            ->relationship()
            ->live()
            ->columns([
                'md' => 10,
            ])
            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                self::updateTotalPrice($get, $set);
            })
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Produk')
                    ->required()
                    ->searchable(['name','sku'])
                    ->searchPrompt('Cari nama atau sku produk')
                    ->preload()
                    ->relationship('product' , 'name')
                    ->getOptionLabelFromRecordUsing(fn (Product $record) => "{$record->name}-({$record->stock})-{$record->sku}")
                    ->columnSpan([
                        'md' => 4
                    ])
                    ->afterStateHydrated(function (Forms\Set $set, Forms\Get $get, $state) {
                        $product = Product::find($state);
                        $set('stock', $product->stock ?? 0);
                    })
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $product = Product::find($state);
                        $set('cost_price', $product->cost_price ?? 0);
                        $set('stock', $product->stock ?? 0);
                        self::updateTotalPrice($get, $set);
                    })
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                Forms\Components\TextInput::make('cost_price')
                    ->label('Harga Modal')
                    ->required()
                    ->numeric()
                    ->prefix('Rp ')
                    ->readOnly()
                    ->columnSpan([
                        'md' => 2
                    ]),
                Forms\Components\TextInput::make('stock')
                    ->label('Stok Saat Ini')
                    ->required()
                    ->numeric()
                    ->readOnly()
                    ->columnSpan([
                        'md' => 2
                    ]),
                Forms\Components\TextInput::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->columnSpan([
                        'md' => 2
                    ])
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        self::updateTotalPrice($get, $set);
                    }),
            ]);
    }

    protected static function updateTotalPrice(Forms\Get $get, Forms\Set $set): void
    {
        $selectedProducts = collect($get('inventoryItems'))->filter(fn($item) => !empty($item['product_id']) && !empty($item['quantity']));

        $prices = Product::find($selectedProducts->pluck('product_id'))->pluck('cost_price', 'id');
        $total = $selectedProducts->reduce(function ($total, $product) use ($prices) {
            return $total + ($prices[$product['product_id']] * $product['quantity']);
        }, 0);

        if ($get('type') !== 'adjustment') {
            $set('total', $total);
        } else {
            $set('total', 0);
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventories::route('/'),
        ];
    }
}
