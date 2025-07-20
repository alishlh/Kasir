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


class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $filter = $this->filters['range'] ?? 'today';

        if ($filter === 'custom') {
            $startDate = !is_null($this->filters['startDate'] ?? null)
                ? Carbon::parse($this->filters['startDate']) : now()->startOfDay();
            $endDate = !is_null($this->filters['endDate'] ?? null)
                ? Carbon::parse($this->filters['endDate'])->endOfDay() : now()->endOfDay();
        } else {
            [$startDate, $endDate] = match ($filter) {
                // Filter untuk menampilkan data hari ini
                'today' => [now()->startOfDay(), now()->endOfDay()],
                // Filter untuk menampilkan data minggu ini
                'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
                // Filter untuk menampilkan data bulan ini
                'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
                // Filter untuk menampilkan data tahun ini
                'this_year' => [now()->startOfYear(), now()->endOfYear()],
                
                default => [now()->startOfDay(), now()->endOfDay()],
            };
        }

        $dataPriceOrder = Transaction::whereBetween('created_at', [$startDate, $endDate])->get();
        $dataPriceExpense = CashFlow::where('type', 'expense')->whereBetween('created_at', [$startDate, $endDate])->get();
        $dataPriceInFLow = CashFlow::where('type', 'income')->whereBetween('created_at', [$startDate, $endDate])->get();
        
        $omset = $dataPriceOrder->sum('total') ?? 0;
        $inFlow = $dataPriceInFLow->sum('amount') ?? 0;
        $outFlow = $dataPriceExpense->sum('amount') ?? 0;
        
        return [
            Stat::make('Penjualan', 'Rp ' . number_format($omset, 0, ",", "."))
            ->description('Omset')
            ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
            ->chart($dataPriceOrder->pluck('total')->toArray())
            ->color('success'),
            Stat::make('Uang Masuk', 'Rp ' . number_format($inFlow, 0, ",", "."))
            ->description('Cash Inflow')
            ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
            ->chart($dataPriceInFLow->pluck('amount')->toArray())
            ->color('success'),
            Stat::make('Uang Keluar', 'Rp ' . number_format($outFlow, 0, ",", "."))
            ->description('Cash Outflow')
            ->descriptionIcon('heroicon-m-arrow-trending-down', IconPosition::Before)
            ->chart($dataPriceExpense->pluck('amount')->toArray())
            ->color('danger'),
            
        ];
    }
}
