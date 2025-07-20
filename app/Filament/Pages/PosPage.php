<?php

namespace App\Filament\Pages;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class PosPage extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.pos-pages';

    protected static ?string $slug = 'pos';

    protected static ?string $title = 'Halaman Kasir';


    public function getMaxContentWidth(): MaxWidth
{
    return MaxWidth::Full;
}

public function getHeader(): ?View
{
    return view('filament.layouts.layout');
}
   

}
