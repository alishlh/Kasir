<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use App\Models\Product;
use Filament\Actions\Action;
use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Components\Tab;
use Illuminate\Support\Facades\Session;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource;


class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function setFlashMessage()
    {
        $error = Session::get('error');

        if ($error) {
            $this->notify($error, 'danger');
            Session::forget('error');
        }
    }

    public function getTabs(): array
{
    return [
        'all' => Tab::make(),
        'Stock Banyak' => Tab::make()
        ->modifyQueryUsing(fn (Builder $query) => $query->where('stock', '>', 10))
        ->badge(Product::query()->where('stock', '>=', 10)->count())
        ->badgeColor('success'),
        'Stock Sedikit' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where( 'stock', '<', 10 ,)->where('stock', '>', 0))
            ->badge(Product::query()->where('stock', '<', 10)->where('stock', '>', 0)->count())
            ->badgeColor('warning'),
        'Stock Habis' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('stock', '=', 0))
            ->badge(Product::query()->where('stock', '<=', 0)->count())
            ->badgeColor('danger'),
    ];
}
}