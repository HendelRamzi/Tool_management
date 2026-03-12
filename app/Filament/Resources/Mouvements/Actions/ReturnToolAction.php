<?php

namespace App\Filament\Resources\Mouvements\Pages\Actions;

use App\Models\Mouvement;
use App\Models\ReturnMouvement;
use App\Services\MouvementService;
use Filament\Actions\Action;
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
            ToolSelectInput::make(self::getTools(), __('Select the tool to return')),
            ToolTextInput::make(__('Enter the quantity to return'))
                ->hint(fn($get) => $get('tool_id') ? __('Quantité restante à rendre: ', ['quantity' => MouvementService::remainingQuantity($get('tool_id'), auth()->id())])  : null)
                ->maxValue(fn($get) => $get('tool_id') ? MouvementService::remainingQuantity($get('tool_id'), auth()->id()) : null)
        ];
    }
    // 

    public static function make()
    {
        return Action::make(__('Return a tool'))
            ->schema(self::form())
            ->action(function ($data) {
                Mouvement::CreateNewMouvement($data, ReturnMouvement::class);

                // Create a notification to inform the user about the success of the operation
                Notification::make()
                    ->title(__('Tool returned'))
                    ->body(__('The tool has been successfully returned.'))
                    ->success()
                    ->send();
            })
            ->color('warning')
            ->icon(Heroicon::ArrowDown)
            ->requiresConfirmation();
    }
}