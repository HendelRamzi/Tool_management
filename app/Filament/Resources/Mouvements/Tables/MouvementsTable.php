<?php

namespace App\Filament\Resources\Mouvements\Tables;

use App\Enums\UserRole;
use App\Filament\Resources\Mouvements\Pages\Actions\CostumViewAction;
use App\Filament\Resources\Mouvements\Pages\ListMouvements;
use App\Filament\Resources\Tools\ToolResource;
use App\Models\InwardMouvement;
use App\Models\LoanMouvement;
use App\Models\ReturnMouvement;
use App\Services\MouvementService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
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
                    ->label(__('Tool reference'))
                    ->collapsible(),
                Group::make('user.name')
                    ->label(__('User name'))
                    ->collapsible(),
                Group::make('created_at')
                    ->date()
                    ->label(__('Creation date')),
            ])
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label(__('User name')),

                TextColumn::make('mouvementable_type')
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->getStateUsing(fn($record) => $record->getTypeLabel())
                    ->color(fn($record) => $record->typeColor())
                    ->label(__('Type')),

                TextColumn::make('tool.reference')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label(__('Tool reference')),

                TextColumn::make('tool.name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label(__('Tool name')),

                TextColumn::make('mouvementable.quantity')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color("info")
                    ->label(__('Quantity')),

                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
            ])
            ->filters([
                Filter::make('today')
                    ->label(__('Today'))
                    ->toggle()
                    ->query(function (Builder $query) {
                        $query->whereDate('created_at', Carbon::today());
                    }),
                Filter::make('date_range')
                    ->label(__('Date range'))
                    ->schema([
                        DatePicker::make('from')
                            ->label(__('From')),

                        DatePicker::make('until')
                            ->label(__('Until')),
                    ])
                    ->query(function (Builder $query, array $data) {

                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date) =>
                                $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date) =>
                                $query->whereDate('created_at', '<=', $date)
                            );
                    }),
                SelectFilter::make('mouvementable_type')
                    ->label(__('Movement Type'))
                    ->native(false)
                    ->options([
                        LoanMouvement::class => 'Taken',
                        ReturnMouvement::class => 'Returned',
                        InwardMouvement::class => 'Stock In',
                    ]),

                Filter::make('borrowed_not_returned')
                    ->label(__('No returned tools'))
                    ->query(function (Builder $query): Builder {

                        $toolIds = MouvementService::borrowedToolsForUser(auth()->id())->pluck('id');

                        // Si aucun tool valide → on force résultat vide
                        if ($toolIds->isEmpty()) {
                            return $query->whereRaw('1 = 0');
                        }

                        return $query->whereIn('tool_id', $toolIds);
                    }),
            ])
            ->filtersTriggerAction(
                fn(Action $action) => $action
                    ->button()
                    ->label(__('Filter')),
            )
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    CostumViewAction::make(__("see_tool"), Heroicon::CircleStack)
                        ->visible(fn($livewire) => $livewire instanceof ListMouvements)
                        ->url(fn($record) => ToolResource::getUrl('view', ['record' => $record->tool_id])),
                    CostumViewAction::make(__("see_user"), Heroicon::User)
                        ->visible(fn($livewire) => $livewire instanceof ListMouvements || auth()->user()->hasRole(UserRole::super_admin))
                        ->url(fn($record) => ToolResource::getUrl('view', ['record' => $record->tool_id])),
                ])->color("secondary")
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
