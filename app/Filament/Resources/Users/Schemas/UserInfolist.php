<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;


class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('User Information')
                ->description('Detailed information about the user')
                ->columnSpanFull()
                ->collapsible()
                ->columns(3)
                ->schema([

                    TextEntry::make('name')
                        ->label('Code'),
                    TextEntry::make('full_name')
                        ->label('Name'),
                    TextEntry::make('roles.name')
                        ->badge()
                        ->weight(FontWeight::Bold)
                        ->size(TextSize::Large)
                        ->color('warning')
                        ->label('User role'),
                    TextEntry::make('email')
                        ->copyable()
                        ->label('Email Address'),

                ]),
        ]);
    }
}