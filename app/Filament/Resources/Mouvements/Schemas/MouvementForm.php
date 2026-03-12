<?php

namespace App\Filament\Resources\Mouvements\Schemas;

use App\Filament\Resources\Mouvements\Pages\Actions\ReturnToolAction;
use App\Filament\Resources\Mouvements\Pages\Actions\TakeToolAction;
use App\Filament\Resources\Mouvements\Pages\Actions\ToolSelectInput;
use App\Filament\Resources\Mouvements\Pages\Actions\ToolTextInput;
use App\Models\LoanMouvement;
use App\Models\ReturnMouvement;
use App\Models\Tool;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MouvementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('mouvementable_type')
                    ->label(__('Movement Type'))
                    ->placeholder(__('Movement Type'))
                    ->required()

                    ->live()
                    ->options([
                        LoanMouvement::class => __('Take a tool'),
                        ReturnMouvement::class => __('Return a tool'),
                    ])->native(false),


                Section::make(__('Return a tool'))
                    ->visible(fn($get) => $get('mouvementable_type') === ReturnMouvement::class)
                    ->columnSpanFull()
                    ->dehydrated()
                    ->columns(2)
                    ->schema(ReturnToolAction::form()),

                Section::make(__('Take a tool'))
                    ->visible(fn($get) => $get('mouvementable_type') === LoanMouvement::class)
                    ->columnSpanFull()
                    ->dehydrated()
                    ->columns(2)
                    ->schema(TakeToolAction::form()),
            ]);
    }
}
