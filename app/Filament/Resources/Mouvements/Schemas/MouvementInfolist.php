<?php

namespace App\Filament\Resources\Mouvements\Schemas;

use App\Enums\ToolStatus;
use App\Enums\UserRole;
use App\Filament\Resources\Tools\ToolResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\InwardMouvement;
use App\Models\Tool;
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
                    ->label('Mouvement number'),
                TextEntry::make('user.name')
                    ->tooltip(fn($record) => "{$record->user->full_name}")
                    ->label('User name'),
                TextEntry::make('tool.name')
                    ->label('Tool name'),
                    
                TextEntry::make('mouvementable.old_qty')
                    ->visible(fn($record) => $record->mouvementable_type === InwardMouvement::class)
                    ->badge()
                    ->color('warning')
                    ->label('Old quantity'),
                TextEntry::make('mouvementable.quantity')
                    ->badge()
                    ->color('info')
                    ->label('Added quantity'),
                TextEntry::make('mouvementable_type')
                    ->badge()
                    ->weight(FontWeight::Bold)
                    ->size(TextSize::Large) 
                    ->getStateUsing(fn($record) => $record->getTypeLabel())
                    ->color(fn($record) => $record->typeColor())
                    ->label('Type'),

                TextEntry::make('created_at')
                    ->date()
                    ->label('Created at'),

                Section::make("User Details")
                    ->description('Detailed information about the user')
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed()
                    ->columns(3)
                    ->footer([
                        Action::make('goToUser')
                            ->outlined()
                            ->visible(auth()->user()->hasRole(UserRole::super_admin))
                            ->size(Size::Small)
                            ->label('See user')
                            ->color('secondary')
                            ->url(fn($record) => UserResource::getUrl('view', [
                                'record' => $record->user_id,
                            ]))
                    ])
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Code'),
                        TextEntry::make('user.full_name')
                            ->label('Name'),
                        TextEntry::make('user.roles.name')
                            ->badge()
                            ->weight(FontWeight::Bold)
                            ->size(TextSize::Large)
                            ->color('warning')
                            ->label('User role'),
                        TextEntry::make('user.email')
                            ->copyable()
                            ->label('Email Address'),
                    ]),
                Section::make("Tool Details")
                    ->description('Detailed information about the tool')
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed()
                    ->columns(3)
                    ->footer([
                        Action::make('goToTool')
                            ->outlined()
                            ->size(Size::Small)
                            ->label('See tool')
                            ->color('secondary')
                            ->url(fn($record) => ToolResource::getUrl('view', [
                                'record' => $record->tool_id,
                            ]))
                    ])
                    ->schema([
                        TextEntry::make('tool.name')
                            ->copyable()
                            ->label('Tool name'),

                        TextEntry::make('tool.reference')
                            ->copyable()
                            ->weight(FontWeight::Bold)
                            ->label('Tool reference'),

                        TextEntry::make('tool.status')
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
                            ->label('Remaining quantity')
                            ->size(TextSize::Large)
                            ->badge()
                            ->color("info"),

                    ])
            ]);
    }
}
