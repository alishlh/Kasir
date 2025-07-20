<?php

namespace App\Providers;

use App\Models\Report;
use App\Models\Product;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\InventoryItem;
use App\Models\TransactionItem;
use Filament\Support\Assets\Js;
use App\Observers\ReportObserver;
use App\Observers\ProductObserver;
use App\Observers\CategoryObserver;
use App\Observers\InventoryObserver;
use App\Observers\TransactionObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\InventoryItemObserver;
use App\Observers\TransactionItemObserver;
use Filament\Support\Facades\FilamentAsset;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inventory::observe(InventoryObserver::class);
        InventoryItem::observe(InventoryItemObserver::class);
        TransactionItem::observe(TransactionItemObserver::class);
        Transaction::observe(TransactionObserver::class);
        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);
        Report::observe(ReportObserver::class);

        FilamentAsset::register([
            Js::make('printer-thermal', asset('js/printer-thermal.js'))
        ]);

        
    }
}
