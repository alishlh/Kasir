<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\CashFlow;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Observers\TransactionObserver;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;


class TotalStatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getDescription(): ?string
    {
        return 'Total dari semua perhitungan';
    }

    protected function getHeading(): ?string
    {
        return 'Total Keseluruhan';
    }

    protected function getStats(): array
    {
        $totalInFlow = CashFlow::where('type','income')->sum('amount');
        $totalOutFlow = CashFlow::where('type','expense')->sum('amount');
        
        return [
            
            Stat::make('Total Uang Masuk', 'Rp ' . number_format($totalInFlow, 0, ",", ".")),
            Stat::make('Total Uang Keluar', 'Rp ' . number_format($totalOutFlow, 0, ",", ".")),
            Stat::make('Total Uang Toko', 'Rp ' . number_format($totalInFlow - $totalOutFlow ,0,",",".")),
        ];
    }
}
