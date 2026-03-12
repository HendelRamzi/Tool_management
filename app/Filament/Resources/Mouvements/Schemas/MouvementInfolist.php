<?php

namespace App\Filament\Resources\Mouvements\Schemas;

use App\Enums\ToolStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Tools\ToolResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\InwardMouvement;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\TextSize;

class MouvementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextEntry::make('id')
                    ->weight(FontWeight::Bold)
                    ->label(__('Mouvement number')),

                TextEntry::make('user.name')
                    ->tooltip(fn($record) => "{$record->user->full_name}")
                    ->label(__('User name')),

                TextEntry::make('tool.name')
                    ->label(__('Tool name')),
                    
                TextEntry::make('mouvementable.old_qty')
                    ->visible(fn($record) => $record->mouvementable_type === InwardMouvement::class)
                    ->badge()
                    ->color('warning')
                    ->label(__('Old quantity')),

                TextEntry::make('mouvementable.quantity')
                    ->badge()
                    ->color('info')
                    ->label(__('Quantity')),

                TextEntry::make('mouvementable_type')
                    ->badge()
                    ->weight(FontWeight::Bold)
                    ->size(TextSize::Large) 
                    ->getStateUsing(fn($record) => $record->getTypeLabel())
                    ->color(fn($record) => $record->typeColor())
                    ->label(__('Type')),

                TextEntry::make('created_at')
                    ->date()
                    ->label(__('Created at')),

                Section::make(__("User Details"))
                    ->description(__('Detailed information about the user'))
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed()
                    ->columns(3)
                    ->footer([
                        Action::make('goToUser')
                            ->outlined()
                            ->visible(auth()->user()->hasRole(UserRole::super_admin))
                            ->size(Size::Small)
                            ->label(__('See user'))
                            ->color('secondary')
                            ->url(fn($record) => UserResource::getUrl('view', [
                                'record' => $record->user_id,
                            ]))
                    ])
                    ->schema([
                        TextEntry::make('user.name')
                            ->label(__('Code')),
                        TextEntry::make('user.full_name')
                            ->label(__('User name')),
                        TextEntry::make('user.roles.name')
                            ->badge()
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->color('warning')
                            ->label(__('User role')),
                        TextEntry::make('user.email')
                            ->copyable()
                            ->label(__('Email Address')),
                    ]),
                Section::make(__("Tool Details"))
                    ->description(__('Detailed information about the tool'))
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed()
                    ->columns(3)
                    ->footer([
                        Action::make('goToTool')
                            ->outlined()
                            ->size(Size::Small)
                            ->label(__('see_tool'))
                            ->color('secondary')
                            ->url(fn($record) => ToolResource::getUrl('view', [
                                'record' => $record->tool_id,
                            ]))
                    ])
                    ->schema([
                        TextEntry::make('tool.name')
                            ->copyable()
                            ->label(__('Tool name')),

                        TextEntry::make('tool.reference')
                            ->copyable()
                            ->weight(FontWeight::Bold)
                            ->label(__('Tool reference')),

                        TextEntry::make('tool.status')
                            ->label(__('Status'))
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->badge()
                            ->icon(fn(string $state) => match ($state) {
                                ToolStatus::Disponible => 'heroicon-o-check-circle',
                                ToolStatus::NoDisponible => 'heroicon-o-x-circle',
                                ToolStatus::NoFunctionnal => 'heroicon-o-x-circle',
                                ToolStatus::Archived => 'heroicon-o-archive-box',

                            })
                            ->color(fn(string $state): string => match ($state) {
                                ToolStatus::Disponible => 'success',
                                ToolStatus::NoDisponible => 'danger',
                                ToolStatus::NoFunctionnal => 'danger',
                                ToolStatus::Archived => 'warning',
                            }),

                        TextEntry::make('tool.qty')
                            ->label(__('Remaining quantity'))
                            ->size(TextSize::Large)
                            ->badge()
                            ->color("info"),

                    ])
            ]);
    }
}
