<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\TransactionItem;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use App\Services\DirectPrintService;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Repeater;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class TransactionResource extends Resource implements HasShieldPermissions
{
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'restore',
            'restore_any',
            'force_delete',
            'force_delete_any',
        ];
    }

    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $pluralLabel = 'Transaksi';

    protected static ?string $navigationGroup = 'Menejemen keuangan';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            SoftDeletingScope::class,
        ])->orderBy('created_at', 'desc');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->maxLength(255)
                            ->nullable(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Produk dipesan')->schema([
                    self::getItemsRepeater(),
                ])
                ->description('Pastikan Cek Terlebih Dahulu ketersediaan Stok Produk'),


                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('total')
                                    ->required()
                                    ->readOnly()
                                    ->numeric(),
                                Forms\Components\Textarea::make('notes')
                                    ->columnSpanFull(),
                            ])
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Pembayaran')
                            ->schema([
                                Forms\Components\Select::make('payment_method_id')
                                    ->relationship('paymentMethod', 'name')
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        $paymentMethod = PaymentMethod::find($state);
                                        $set('is_cash', $paymentMethod?->is_cash ?? false);

                                        if (!$paymentMethod->is_cash) {
                                            $set('change', 0);
                                            $set('cash_received', $get('total'));
                                        }
                                    })
                                    ->afterStateHydrated(function (Forms\Set $set, Forms\Get $get, $state) {
                                        $paymentMethod = PaymentMethod::find($state);

                                        if (!$paymentMethod?->is_cash) {
                                            $set('cash_received', $get('total'));
                                            $set('change', 0);
                                        }

                                        $set('is_cash', $paymentMethod?->is_cash ?? false);
                                    }),
                                Forms\Components\Hidden::make('is_cash')
                                    ->dehydrated(),
                                Forms\Components\TextInput::make('cash_received')
                                    ->numeric()
                                    ->reactive()
                                    ->label('Nominal Bayar')
                                    ->readOnly(fn(Forms\Get $get) => $get('is_cash') == false)
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                        // function untuk menghitung uang kembalian

                                        self::updateExcangePaid($get, $set);
                                    }),
                                Forms\Components\TextInput::make('change')
                                    ->numeric()
                                    ->label('Kembalian')
                                    ->readOnly(),
                            ])
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_number')
                    ->label('#No.Transaksi')
                    ->weight('semibold')
                    ->prefix('#')
                    ->copyable()
                    ->copyMessage('#No.Transaksi copied')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pemesan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total Harga')
                    ->prefix('Rp ')
                    ->numeric(),
                Tables\Columns\BadgeColumn::make('paymentMethod.name')
                    ->label('Pembayaran')
                    ->numeric(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Dari Tanggal'),
                        DatePicker::make('end_date')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['start_date'], fn($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['end_date'], fn($query, $date) => $query->whereDate('created_at', '<=', $date));
                    }),
                Tables\Filters\TrashedFilter::make()
                ->placeholder('Tanpa return pelanggan')
                ->trueLabel('Beserta return pelanggan')
                ->falseLabel('Hanya return pelanggan'),
            ], layout: Tables\Enums\FiltersLayout::Modal)
            ->actions([
                Tables\Actions\Action::make('PrintBluetooth')
                    ->label('Cetak')
                    ->hidden(fn() => Setting::first()->value('print_via_bluetooth') == false) // Ambil nilai dari model lain
                    ->action(function ($record, $livewire) {
                        $order = Transaction::with(['paymentMethod', 'transactionItems.product'])->findOrFail($record->id);
                        $items = $order->transactionItems;


                        $livewire->printStruk($order,$items);
                    })
                    ->icon('heroicon-o-printer')
                    ->color('amber'),
                Tables\Actions\Action::make('Print')
                    ->label('Cetak')
                    ->hidden(fn() => Setting::first()->value('print_via_bluetooth')) // Ambil nilai dari model lain
                    ->action(function (Transaction $record) {
                        $directPrint = app(DirectPrintService::class);
                        $directPrint->print($record->id);
                    })
                    ->icon('heroicon-o-printer')
                    ->color('amber'),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->visible(fn($record) => !$record->trashed()),
                    Tables\Actions\ViewAction::make()
                        ->color('warning')
                        ->label('Detail'),
                    Tables\Actions\DeleteAction::make()
                    ->label('Return pelanggan'),
                    Tables\Actions\ForceDeleteAction::make()
                    ->visible()
                    ->label('Batalkan Transaksi'),
                    Tables\Actions\RestoreAction::make(),
                ])
                ->tooltip('Tindakan'),
            ])
            ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make()
                    ->label('Return Pelanggan')
                    ->button(),
                    Tables\Actions\ForceDeleteBulkAction::make()
                    ->visible()
                    ->label('Batalkan Transaksi')
                    ->button(),
                    Tables\Actions\RestoreBulkAction::make(),
                    // ...
            ])
            ->headerActions([]);
    }

    
     public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('transactionItems')
            ->hiddenLabel()
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
                    ->options(function (Forms\Get $get) {
                        $selectedId = $get('product_id');
                        // Ambil produk aktif (stock > 1)
                        $productsQuery = Product::query()
                            ->where('stock', '>', 0);
                        // Jika produk yang sedang dipilih sudah soft deleted, tetap sertakan
                        if ($selectedId) {
                            $productsQuery->orWhere('id', $selectedId);
                        }
                        // Ambil semua termasuk soft deleted
                        return $productsQuery->pluck('name', 'id');
                    })

                    ->columnSpan([
                        'md' => 5
                    ])
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $product = Product::withTrashed()->find($state);
                        $set('cost_price', $product->cost_price ?? 0);
                        $set('price', $product->price ?? 0);
                        $set('total_profit', ($product->price ?? 0) - ($product->cost_price ?? 0) ?? 0);
                        self::updateTotalPrice($get, $set);
                    })
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->columnSpan([
                        'md' => 5
                    ])
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $id = $get('product_id');
                        $product = Product::withTrashed()->find($id);
                            $quantity = (int) ($get('quantity') ?? 0);
                            $price = (int) ($product->price ?? 0);
                            $costPrice = (int) ($product->cost_price ?? 0);
                            $set('total_profit', ($price - $costPrice) * $quantity);
                            self::updateTotalPrice($get, $set);
                    }),
                Forms\Components\TextInput::make('cost_price')
                    ->label('Harga Modal')
                    ->required()
                    ->numeric()
                    ->readOnly()
                    ->columnSpan([
                        'md' => 3
                    ]),
                Forms\Components\TextInput::make('price')
                    ->label('Harga jual')
                    ->required()
                    ->numeric()
                    ->readOnly()
                    ->columnSpan([
                        'md' => 3
                    ]),
                Forms\Components\TextInput::make('total_profit')
                    ->label('Profit')
                    ->required()
                    ->numeric()
                    ->readOnly()
                    ->columnSpan([
                        'md' => 3
                    ]),

            ])
            ->mutateRelationshipDataBeforeSaveUsing(function (array $data) {
            $invalidProducts = collect($data['transactionItems'] ?? [])
                ->filter(function ($item) {
                    $product = Product::withTrashed()->find($item['product_id']);
                    return !$product || $product->trashed();
                });

            if ($invalidProducts->isEmpty()) {
                Notification::make()
                    ->title('Tidak dapat menyimpan')
                    ->body('Ada produk yang telah dihapus dari sistem.')
                    ->danger()
                    ->send();

                throw new Halt('Produk tidak valid.');
            }

            return $data;
        });
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('transaction_number')
                    ->label('No.Transaksi :')
                    ->badge()
                    ->color('primary')
                    ->weight(FontWeight::Bold),
                TextEntry::make('name')
                    ->label('Nama Customer :')
                    ->badge()
                    ->color('primary')
                    ->weight(FontWeight::Bold),
                TextEntry::make('paymentMethod.name')
                    ->label('Metode Pembayaran :')
                    ->badge()
                    ->color('primary')
                    ->weight(FontWeight::Bold),
                TextEntry::make('created_at')
                    ->label('Tanggal Transaksi:')
                    ->badge()
                    ->color('primary')
                    ->weight(FontWeight::Bold),
            ])->columns(4);
    }

    public static function getRelations(): array
    {
        return [
            TransactionResource\RelationManagers\TransactionItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('edit/{record}'),
        ];
    }


   protected static function updateTotalPrice(Forms\Get $get, Forms\Set $set): void
{
    $selectedProducts = collect($get('transactionItems'))
        ->filter(fn($item) => !empty($item['product_id']) && !empty($item['quantity']));

    $ids = $selectedProducts->pluck('product_id')->all();
    $products = Product::withTrashed()->whereIn('id', $ids)->get();

    $missingProducts = $selectedProducts->filter(fn($item) => !$products->contains('id', $item['product_id']));

    if ($missingProducts->isNotEmpty()) {
        Notification::make()
            ->title('Beberapa produk tidak tersedia')
            ->danger()
            ->send();
    }

    $prices = $products->pluck('price', 'id');
    $total = $selectedProducts->reduce(function ($total, $item) use ($prices) {
        $productId = $item['product_id'];
        $price = $prices[$productId] ?? 0;
        $quantity = $item['quantity'];
        return $total + ($price * $quantity);
    }, 0);

    $set('total', $total);
}


    protected static function updateExcangePaid(Forms\Get $get, Forms\Set $set): void
    {
        $paidAmount = (int) $get('cash_received') ?? 0;
        $totalPrice = (int) $get('total') ?? 0;
        $exchangePaid = $paidAmount - $totalPrice;
        $set('change', $exchangePaid);
    }
}
