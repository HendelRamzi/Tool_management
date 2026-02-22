<?php

namespace App\Filament\Resources\Mouvements\Pages\Actions;

use Filament\Forms\Components\Select;


class ToolSelectInput
{
    public static function make(array $options = null, string $placeholder = 'Select the tool to take'): Select
    {
        return Select::make('tool_id')
            ->label('Tool')
            ->live()
            ->placeholder($placeholder)
            ->native(false)
            ->options($options)
            ->searchable(['name', 'reference'])
            ->required();
    }
}