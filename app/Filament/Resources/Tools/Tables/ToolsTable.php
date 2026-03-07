<?php

namespace App\Filament\Resources\Tools\Tables;

use App\Enums\ToolStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Tools\Actions\ToolDeleteAction;
use App\Models\Tool;
use Faker\Core\Color;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ToolsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                TextColumn::make('name')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->wrap(false)
                    ->sortable()
                    ->limit(30)
                    ->searchable(),
                TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->wrap(false)
                    ->limit(30),
                TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->dateTime('d/m/Y')
                    ->sortable(),
                TextColumn::make('qty')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Quantity')
                    ->sortable()
                    ->color(fn(int $state) => Tool::ColorQtyMapping($state))
                    ->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(fn(string $state): string => match ($state) {
                        ToolStatus::Disponible => 'success',
                        ToolStatus::NoDisponible => 'danger',
                        ToolStatus::NoFunctionnal => 'danger',
                        ToolStatus::Archived => 'warning',
                    })
                    ->icon(fn(string $state) => match ($state) {
                        ToolStatus::Disponible => 'heroicon-o-check-circle',
                        ToolStatus::NoDisponible => 'heroicon-o-x-circle',
                        ToolStatus::NoFunctionnal => 'heroicon-o-x-circle',
                        ToolStatus::Archived => 'heroicon-o-archive-box',
                    })->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                TrashedFilter::make()
                    ->native(false)
                    ->visible(auth()->user()->hasRole(UserRole::super_admin)),
            ])->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    ViewAction::make(),
                    ToolDeleteAction::make(true),
                ])->color("secondary")
            ])
            ->toolbarActions([

            ]);
    }
}
