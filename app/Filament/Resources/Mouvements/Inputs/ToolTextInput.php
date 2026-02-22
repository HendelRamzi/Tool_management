<?php

namespace App\Filament\Resources\Mouvements\Pages\Actions;

use Filament\Forms\Components\TextInput;


class ToolTextInput
{
    public static function make(string $placeholder = ""): TextInput
    {
        return TextInput::make('qty')
            ->label('Quantity')
            ->placeholder($placeholder)
            ->numeric()
            ->live()
            ->default(1)
            ->minValue(1)
            ->required();
    }
}