<?php

namespace App\Filament\Widgets;

use Filament\Forms;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CashFlowRadarChart extends ChartWidget
{
    protected static ?string $heading = 'Arus Kas';
    protected static ?string $description = 'Total Jumlah Pergerakan Alur Kas';
    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $data = DB::table('cash_flows')
            ->select('source', 'type', DB::raw('SUM(amount) as total'))
            ->groupBy('source', 'type')
            ->get();

        // Ambil label source unik
        $sources = $data->pluck('source')->unique()->values();

        // Gabungkan income dan expense per source (jumlah bersih)
        $totalPerSource = [];

        foreach ($sources as $source) {
            $income = $data->where('source', $source)->where('type', 'income')->sum('total');
            $expense = $data->where('source', $source)->where('type', 'expense')->sum('total');
            $totalPerSource[] = $income - $expense;
        }

        return [
            'labels' => $sources,
            'datasets' => [
                [
                    'label' => 'Net Cash Flow per Source',
                    'data' => $totalPerSource,
                    'backgroundColor' => [
                        'rgb(52, 211, 153)',
                        'rgb(251, 191, 36)',
                        'rgb(239, 68, 68)',
                        'rgb(96, 165, 250)',
                        'rgb(168, 85, 247)',
                        'rgb(236, 72, 153)',
                        'rgb(34, 197, 94)',
                        'rgb(253, 224, 71)',
                        'rgb(250, 204, 21)',
                        'rgb(29, 78, 216)',
                        'rgb(124, 58, 237)',
                        'rgb(236, 72, 153)',
                        'rgb(16, 185, 129)',
                        'rgb(59, 130, 246)',
                        'rgb(232, 62, 140)',

                    ],
                    'borderColor' => 'rgba(0, 0, 0, 0.1)',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
