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
                    ->label(__('Tool name'))
                    ->required()
                    ->placeholder(__('Enter tool name')),

                TextInput::make('qty')
                    ->label(__('Quantity'))
                    ->visible(fn($livewire) => $livewire instanceof EditTool)
                    ->numeric()
                    ->readOnly()
                    ->placeholder(__('Enter the tool quantity')),

                TextInput::make('reference')
                    ->label(__("Tool reference"))
                    ->placeholder(__('Enter the tool reference'))
                    ->required(),

                Textarea::make('description')
                    ->label(__('Description'))
                    ->placeholder(  __('Write your discription (Optional)'))
                    ->columnSpanFull(),
            ]);
    }
}
