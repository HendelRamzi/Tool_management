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
            Section::make(__("User Details"))
                ->description(__('Detailed information about the user'))
                ->columnSpanFull()
                ->collapsible()
                ->columns(3)
                ->schema([

                    TextEntry::make('name')
                        ->label(__('Code')),
                    TextEntry::make('full_name')
                        ->label(__('User name')),
                    TextEntry::make('roles.name')
                        ->badge()
                        ->label(__('User role'))
                        ->weight(FontWeight::Bold)
                        ->size(TextSize::Large)
                        ->color('warning'),
                    TextEntry::make('email')
                        ->copyable()
                        ->label(__('Email Address')),

                ]),
        ]);
    }
}