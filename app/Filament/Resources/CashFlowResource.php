<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\CashFlow;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use App\Services\CashFlowLabelService;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CashFlowResource\Pages;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class CashFlowResource extends Resource implements HasShieldPermissions
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
    
    protected static ?string $model = CashFlow::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Alur Kas';

    protected static ?string $navigationGroup = 'Menejemen keuangan';

    public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\ToggleButtons::make('type')
                    ->options(CashFlowLabelService::getTypes())
                    ->colors([
                        'income' => 'success',
                        'expense' => 'danger',
                    ])
                    ->default('income')
                    ->grouped()
                    ->live(),
                Forms\Components\Select::make('source')
                    ->options(fn(Get $get) => CashFlowLabelService::getSourceOptionsByType($get('type'))),
                Forms\Components\TextInput::make('amount')
                    ->prefix('Rp ')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date('d F Y')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                ->formatStateUsing(fn($state) => CashFlowLabelService::getTypeLabel($state))
                ->colors([
                    'success' => CashFlowLabelService::TYPE_INCOME,
                    'danger' => CashFlowLabelService::TYPE_EXPENSE,
                ])
                ->icon(fn (string $state): string => match ($state) {
                        CashFlowLabelService::TYPE_INCOME => 'heroicon-o-arrow-down-circle',
                        CashFlowLabelService::TYPE_EXPENSE => 'heroicon-o-arrow-up-circle',
                    }),
                Tables\Columns\TextColumn::make('source')
                    ->formatStateUsing(fn($state, $record) => CashFlowLabelService::getSourceLabel($record->type,$state))
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('amount')
                    ->prefix('Rp ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('range_date')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Dari Tanggal'),
                        DatePicker::make('end_date')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['start_date'], fn($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['end_date'], fn($query, $date) => $query->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(fn(array $data): ?string =>
                        $data['start_date'] ? 'Dari ' . Carbon::parse($data['start_date'])->toFormattedDateString() . ($data['end_date'] ? ' Sampai ' . Carbon::parse($data['end_date'])->toFormattedDateString() : '')
                        : ($data['end_date'] ? 'Sampai ' . Carbon::parse($data['end_date'])->toFormattedDateString() : null)),
                Tables\Filters\Filter::make('SourceTipe')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->label('Tipe')
                            ->options(CashFlowLabelService::getTypes())
                            ->placeholder('Semua Tipe'),
                        Forms\Components\Select::make('source')
                            ->label('Sumber')
                            ->options(fn(Get $get) => CashFlowLabelService::getSourceOptionsByType($get('type')))
                            ->placeholder('Semua Sumber')
                            ->disabled(fn(Get $get) => empty($get('type'))),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $type = $data['type'] ?? null;
                        $source = $data['source'] ?? null;
                        if (empty($type)) {
                            return $query;
                        }
                        $query->where('type', $type);
                        if (!empty($source)) {
                            $query->where('source', $source);
                        }
                        return $query;
                    })
                    ->indicateUsing(
                        fn(array $data): ?string => ($data['type'] ? 'Tipe: ' . CashFlowLabelService::getTypeLabel($data['type']) : null) .
                            ($data['source'] ? ', Sumber: ' . CashFlowLabelService::getSourceLabel($data['type'], $data['source']) : '')
                    )
            ], layout: Tables\Enums\FiltersLayout::Modal)
            ->actions([
                Tables\Actions\EditAction::make()
                ->visible(fn($record) => 
                $record->source !== 'sales' &&
                $record->source !== 'adjustment' &&
                $record->source !== 'restored_sales' &&
                $record->source !== 'refund' &&
                $record->source !== 'purchase_stock' 
            ),
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
            'index' => Pages\ListCashFlows::route('/'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            CashFlowResource\Widgets\IncomeOverview::class,
        ];
    }
}
