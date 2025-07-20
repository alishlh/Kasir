<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PaymentMethodPieChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Transaksi per Metode Pembayaran';
    protected static ?string $description = 'Total Jumlah Transaksi per Metode Pembayaran';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        // Ambil data transaksi yang dikelompokkan berdasarkan metode pembayaran
        $data = Transaction::selectRaw('payment_method_id, COUNT(*) as total')
            ->groupBy('payment_method_id')
            ->with('paymentMethod')
            ->get();

        // Label dan data untuk chart
        $labels = $data->map(fn ($item) => $item->paymentMethod->name ?? 'Tidak diketahui')->toArray();
        $values = $data->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $values,
                    'backgroundColor' => [
                        '#f87171', // merah
                        '#60a5fa', // biru
                        '#34d399', // hijau
                        '#facc15', // kuning
                        '#c084fc', // ungu
                    ],
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => $labels,
        ];
    }


    protected function getType(): string
    {
        return 'doughnut';
    }
}
