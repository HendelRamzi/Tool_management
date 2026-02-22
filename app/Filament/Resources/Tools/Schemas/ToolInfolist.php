<?php

namespace App\Filament\Resources\Tools\Schemas;

use App\Enums\ToolStatus;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

class ToolInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Tool Information')
                ->description('Detailed information about the tool')
                ->columnSpanFull()
                ->collapsible()
                ->columns(3)
                ->schema([
                    TextEntry::make('name')
                        ->copyable()
                        ->label('Tool name'),
                    TextEntry::make('reference')
                        ->copyable()
                        ->weight(FontWeight::Bold)
                        ->columnSpan(2)
                        ->label('Tool reference'),
                    TextEntry::make('status')
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
                    TextEntry::make('qty')
                        ->label('Quantity')
                        ->size(TextSize::Large)
                        ->badge()
                        ->color("info"),
                    TextEntry::make('created_at')
                        ->date()
                ]),
        ]);
    }
}