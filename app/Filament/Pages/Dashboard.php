<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;


class Dashboard extends BaseDashboard
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    use BaseDashboard\Concerns\HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('range')
                            ->label(' Waktu')
                            ->options([
                                'today' => 'Hari Ini',
                                'this_week' => 'Minggu Ini',
                                'this_month' => 'Bulan Ini',
                                'this_year' => 'Tahun Ini',
                                'custom' => 'Manual / Custom',
                            ])
                            ->default('today'),

                        DatePicker::make('startDate')
                            ->label('Dari Tanggal')
                            ->visible(fn(Get $get) => $get('range') === 'custom')
                            ->maxDate(fn(Get $get) => $get('endDate') ?: now()),

                        DatePicker::make('endDate')
                            ->label('Sampai Tanggal')
                            ->visible(fn(Get $get) => $get('range') === 'custom')
                            ->minDate(fn(Get $get) => $get('startDate') ?: now())
                            ->maxDate(now()),
                    ])
                    ->columns(3),
            ]);
    }
}
