<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms\Form;
use Milon\Barcode\DNS1D;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class ProductResource extends Resource implements HasShieldPermissions
{
    public static function getPermissionPrefixes(): array
    {
        return [
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
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationLabel = 'Produk';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Manajemen Produk';


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
                Forms\Components\TextInput::make('name')
                    ->label('Nama Obat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->label('Kategori Produk (Rak)')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\Section::make('Harga')
                    ->schema([
                        Forms\Components\TextInput::make('cost_price')
                            ->label('Harga Modal')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('price')
                            ->label('Harga Eceran (Umum)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        Forms\Components\TextInput::make('price_2')
                            ->label('Harga Grosir (Bidan/Dokter)')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\TextInput::make('price_racikan') // KONSISTEN
                            ->label('Harga Racikan')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),
                    ])->columns(2),

                Forms\Components\TextInput::make('stock')
                    ->label('Stok Produk')
                    ->helperText('Stok hanya dapat diisi/ditambah pada menejemen inventori')
                    ->required()
                    ->numeric()
                    ->readOnly()
                    ->default(0),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->helperText('jika tidak diisi akan di generate otomatis')
                    ->maxLength(255),
                Forms\Components\TextInput::make('barcode')
                    ->label('Kode Barcode')
                    ->numeric()
                    ->helperText('jika tidak diisi akan di generate otomatis')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('expiry_date')
                    ->displayFormat('d/m/Y')
                    ->label('Tanggal Kadaluarsa')
                    ->native(false)
                    ->minDate(now())
                    ->required()
                    ->placeHolder('Masukkan tanggal kadaluarsa produk'),
                Forms\Components\TextInput::make('no_batch')
                    ->label('No Batch')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Produk Aktif')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi Produk')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Status')
                    ->getStateUsing(function (Product $record) {
                        if (is_null($record->expiry_date)) {
                            return '';
                        }

                        $expiryDate = \Carbon\Carbon::parse($record->expiry_date);

                        if ($expiryDate->isPast()) {
                            return 'Expired';
                        }

                        // Optional: You can add logic here to show "Will expire soon" if needed
                        // For example, if expiry is within 30 days
                        if ($expiryDate->diffInDays(now()) <= 30) {
                            return 'Will expire soon';
                        }

                        return 'Active';
                    })
                    ->color(function (Product $record) {
                        if (is_null($record->expiry_date)) {
                            return null;
                        }

                        $expiryDate = \Carbon\Carbon::parse($record->expiry_date);

                        if ($expiryDate->isPast()) {
                            return 'danger'; // Red color for expired
                        }

                        // Optional: Yellow color for items that will expire soon
                        if ($expiryDate->diffInDays(now()) <= 30) {
                            return 'warning';
                        }

                        return 'success'; // Green color for active
                    })
                    ->description(function (Product $record) {
                        if (!is_null($record->expiry_date)) {
                            return 'Expiry: ' . \Carbon\Carbon::parse($record->expiry_date)->format('d M Y');
                        }
                        return null;
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Obat')
                    ->description(fn(Product $record): string => $record->category()->withTrashed()->value('name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->description(fn(Product $record): string => $record->category()->withTrashed()->value('name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_price')
                    ->label('Harga Modal')
                    ->prefix('Rp ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Jual')
                    ->prefix('Rp ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('barcode')
                    ->label('No.Barcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Produk Aktif'),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->label('Tanggal Kadaluarsa')
                    ->date('d-m-Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_batch')
                    ->label('No Batch')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->searchable(),

            ])
            ->actions([
                Tables\Actions\Action::make('Reset Stok')
                    ->action(fn(Product $record) => $record->update(['stock' => 0]))
                    ->button()
                    ->color('info')
                    ->requiresConfirmation(),
                Tables\Actions\EditAction::make()
                    ->button(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
                Tables\Actions\BulkAction::make('printBarcodes')
                    ->label('Cetak Barcode')
                    ->button()
                    ->icon('heroicon-o-printer')
                    ->action(fn($records) => self::generateBulkBarcode($records))
                    ->color('success'),

                Tables\Actions\BulkAction::make('Reset Stok')
                    ->action(fn($records) => $records->each->update(['stock' => 0]))
                    ->button()
                    ->color('info')
                    ->requiresConfirmation(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('printBarcodes')
                    ->label('Cetak Barcode')
                    ->icon('heroicon-o-printer')
                    ->action(fn() => self::generateBulkBarcode(Product::all()))
                    ->color('success'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
        ];
    }

    protected static function generateBulkBarcode($records)
    {
        $barcodes = [];
        $barcodeGenerator = new DNS1D();

        foreach ($records as $product) {
            $barcodes[] = [
                'name' => $product->name,
                'price' => $product->price,
                'barcode' => 'data:image/png;base64,' . $barcodeGenerator->getBarcodePNG($product->barcode, 'C128'),
                'number' => $product->barcode
            ];
        }

        // Generate PDF
        $pdf = Pdf::loadView('pdf.barcodes.barcode', compact('barcodes'))->setPaper('a4', 'portrait');

        // Kembalikan response download tanpa metode header()
        return response()->streamDownload(fn() => print ($pdf->output()), 'barcodes.pdf');
    }
}
