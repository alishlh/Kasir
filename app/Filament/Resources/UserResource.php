<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Illuminate\Support\Facades\Gate;
use Filament\Notifications\Notification;

class UserResource extends Resource implements HasShieldPermissions
{
    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'create',
            'update',
            'delete_any',
            'toggle_active',
        ];
    }

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Pengaturan Toko';

    public static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                Forms\Components\Select::make('roles')
                    ->label('Peran')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable(),
                Forms\Components\Select::make('status')
                    ->label('Status Aktif')
                    ->options([
                        'active' => 'Active',
                        'non-active' => 'Non-Active',
                    ])
                    ->required()
                    ->native(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Peran'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'non-active' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'active' => 'Active',
                        'non-active' => 'Non-Active',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Aktif')
                    ->options([
                        'active' => 'Active',
                        'non-active' => 'Non-Active',
                    ])
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('md'),
                Tables\Actions\Action::make('toggleActive')
                    ->label('Toggle Status')
                    ->icon(fn(User $record) => $record->status === 'active' ? 'heroicon-s-check-circle' : 'heroicon-s-x-circle')
                    ->color(fn(User $record) => $record->status === 'active' ? 'success' : 'danger')
                    ->action(function (User $user) {
                        $user->update([
                            'status' => $user->status === 'active' ? 'non-active' : 'active'
                        ]);

                        Notification::make()
                            ->title('Status berhasil diubah')
                            ->body($user->status === 'active'
                                ? 'User telah diaktifkan'
                                : 'User telah dinonaktifkan')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Ubah Status User')
                    ->modalDescription(fn(User $record) => $record->status === 'active'
                        ? 'Apakah Anda yakin ingin menonaktifkan user ini?'
                        : 'Apakah Anda yakin ingin mengaktifkan user ini?')
                    ->modalSubmitActionLabel(fn(User $record) => $record->status === 'active'
                        ? 'Ya, nonaktifkan'
                        : 'Ya, aktifkan')
                    ->visible(fn(): bool => Gate::allows('toggle_active', User::class)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan yang dipilih')
                        ->icon('heroicon-s-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['status' => 'active']);

                            Notification::make()
                                ->title('User berhasil diaktifkan')
                                ->body(count($records) . ' user telah diaktifkan')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalDescription('Apakah Anda yakin ingin mengaktifkan user yang dipilih?'),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Nonaktifkan yang dipilih')
                        ->icon('heroicon-s-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each->update(['status' => 'non-active']);

                            Notification::make()
                                ->title('User berhasil dinonaktifkan')
                                ->body(count($records) . ' user telah dinonaktifkan')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalDescription('Apakah Anda yakin ingin menonaktifkan user yang dipilih?'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
        ];
    }
}
