<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditStoreProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Profil Toko';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Toko')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug (URL)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('Alamat')
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Telepon')
                    ->tel()
                    ->maxLength(20),
                FileUpload::make('logo')
                    ->label('Logo Toko')
                    ->image()
                    ->directory('store-logos')
                    ->maxSize(2048),
            ]);
    }
}
