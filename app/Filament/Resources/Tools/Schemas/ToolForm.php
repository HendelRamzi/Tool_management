<?php

namespace App\Filament\Resources\Tools\Schemas;

use App\Filament\Resources\Tools\Pages\EditTool;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ToolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->placeholder('Enter tool name'),

                TextInput::make('qty')
                    ->label('Quantity')
                    ->visible(fn($livewire) => $livewire instanceof EditTool)
                    ->numeric()
                    ->readOnly()
                    ->placeholder('Enter the tool quantity'),

                TextInput::make('reference')
                    ->placeholder('Enter the tool reference')
                    ->required(),

                Textarea::make('description')
                    ->placeholder('Write your discription (Optional)')
                    ->columnSpanFull(),
            ]);
    }
}
