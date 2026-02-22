<?php

namespace App\Filament\Resources\Mouvements\Pages\Actions;

use App\Models\Mouvement;
use App\Models\ReturnMouvement;
use App\Services\MouvementService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class ReturnToolAction
{

    public static function getTools()
    {
        return MouvementService::borrowedToolsForUser(auth()->id())
            ->mapWithKeys(fn($tool) => [
                $tool->id => "{$tool->name} - {$tool->reference}"
            ])->toArray();
    }

    public static function form()
    {
        return [
            ToolSelectInput::make(self::getTools(), 'Select the tool to return'),
            ToolTextInput::make('Select the product quantity')
                ->hint(fn($get) => $get('tool_id') ? 'Quantité restante à rendre : ' . MouvementService::remainingQuantity($get('tool_id'), auth()->id()) : null)
                ->maxValue(fn($get) => $get('tool_id') ? MouvementService::remainingQuantity($get('tool_id'), auth()->id()) : null)
        ];
    }

    public static function make()
    {
        return Action::make('Return a tool')
            ->schema(self::form())
            ->action(function ($data) {
                Mouvement::CreateNewMouvement($data, ReturnMouvement::class);

                // Create a notification to inform the user about the success of the operation
                Notification::make()
                    ->title('Tool returned')
                    ->body('The tool has been successfully returned.')
                    ->success()
                    ->send();
            })
            ->color('warning')
            ->icon(Heroicon::ArrowDown)
            ->requiresConfirmation();
    }
}