<?php

namespace App\Filament\Resources\Mouvements\Tables;

use App\Enums\UserRole;
use App\Models\Mouvement;
use App\Services\MouvementService;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
class MouvementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultGroup("tool.reference")
            ->groups([
                Group::make('tool.reference')
                    ->label('Tool reference')
                    ->collapsible(),
                Group::make('user.name')
                    ->label('User name')
                    ->collapsible(),
                Group::make('created_at')
                    ->date()
                    ->label('Date'),
            ])
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->label('User name'),

                TextColumn::make('mouvementable_type')
                    ->badge()
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->getTypeLabel())
                    ->color(fn($record) => $record->typeColor())
                    ->label('Type'),

                TextColumn::make('tool.reference')
                    ->searchable()
                    ->label('Tool reference'),

                TextColumn::make('tool.name')
                    ->searchable()
                    ->label('Tool name'),

                TextColumn::make('mouvementable.quantity')
                    ->badge()
                    ->color("info")
                    ->label('Quantity'),

                TextColumn::make('created_at')
                    ->label('Created at')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('borrowed_not_returned')
                    ->label('No returned tools')
                    ->query(function (Builder $query): Builder {

                        $toolIds = MouvementService::borrowedToolsForUser(auth()->id())->pluck('id');

                        // Si aucun tool valide → on force résultat vide
                        if ($toolIds->isEmpty()) {
                            return $query->whereRaw('1 = 0');
                        }

                        return $query->whereIn('tool_id', $toolIds);
                    }),
            ], FiltersLayout::Modal)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    DeleteAction::make(),
                ])->color("secondary")
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
