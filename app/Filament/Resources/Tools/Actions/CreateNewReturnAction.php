<?php

namespace App\Filament\Resources\Tools\Actions;


use App\Filament\Resources\Mouvements\Pages\Actions\ToolTextInput;
use App\Models\Mouvement;
use App\Models\ReturnMouvement;
use App\Services\MouvementService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class CreateNewReturnAction
{
    public static function make($withIcon = false, $record)
    {
        return Action::make("create_new_return")
            ->color('danger')
            ->label('Return a tool')
            ->requiresConfirmation()
            ->icon($withIcon ? Heroicon::Minus : null)
            ->schema([
                ToolTextInput::make('Select the product quantity')
                    ->hint(fn()  => !is_null($record) ? 'Quantité restante à rendre : ' . MouvementService::remainingQuantity($record->id, auth()->id()) : null)
                    ->maxValue(fn() => !is_null($record) ? MouvementService::remainingQuantity($record->id, auth()->id()) : null)
            ])
            ->action(function ($data) use ($record) {
                $data['tool_id'] = $record->id;
                Mouvement::CreateNewMouvement($data, ReturnMouvement::class);

                // Create a notification to inform the user about the success of the operation
                Notification::make()
                    ->title('Tool returned')
                    ->body('The tool has been successfully returned.')
                    ->success()
                    ->send();
            });
    }
}