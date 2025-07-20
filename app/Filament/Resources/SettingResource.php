<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Setting;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\SettingResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;


class SettingResource extends Resource implements HasShieldPermissions
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

    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-printer';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationGroup = 'Pengaturan Toko';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profil Toko')
                ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Toko'),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255)
                    ->label('Alamat Toko'),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255)
                    ->label('Nomor Telepon'),
                ]),
                Forms\Components\Section::make('Setting Printer')
                ->schema([
                Forms\Components\ToggleButtons::make('print_via_bluetooth')
                    ->required()
                    ->label('Tipe Print')
                    ->options([
                        0 => 'Kabel (Server Local)',
                        1 => 'Bluetooth'
                    ])
                    ->grouped()
                    ->helperText('Pastikan setiap masuk halaman kasir sambungkan bluetooth terlebih dahulu')
                    ->live(),
                Forms\Components\TextInput::make('name_printer_local')
                    ->maxLength(255)
                    ->label('Nama Printer (Khusus untuk kabel)')
                    ->helperText('Samakan dengan nama printer yang anda gunakan dan sudah terdaftar atau terhubung di server yang sama. Contoh: Epson T20')
                    ->hidden(fn (Get $get) => $get('print_via_bluetooth') == true), // Disembunyikan jika print_via_mobile bernilai true
                Forms\Components\FileUpload::make('logo')
                    ->image()
                    ->required()
                    ->helperText('Pastikan format gambar adalah PNG')
                    ->directory('images')
                    ->label('Logo Toko'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->circular()
                    ->label('Logo Toko'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Toko')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat Toko')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->searchable(),
                Tables\Columns\IconColumn::make('print_via_bluetooth')
                    ->label('Print Via Bluetooth')
                    ->boolean(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return Setting::count() < 1;
    }
}
