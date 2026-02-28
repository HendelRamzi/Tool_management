<?php

namespace App\Filament\Resources\Tools\Actions;

use App\Filament\Resources\Mouvements\Pages\Actions\TakeToolAction;
use App\Filament\Resources\Mouvements\Pages\Actions\ToolSelectInput;
use App\Filament\Resources\Mouvements\Pages\Actions\ToolTextInput;
use App\Models\LoanMouvement;
use App\Models\Mouvement;
use App\Models\Tool;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class CreateNewLoanAction
{
    public static function make($withIcon = false, $record)
    {
        return Action::make("create_new_loan")
            ->color('success')
            ->label('Take the tool')
            ->requiresConfirmation()
            ->icon($withIcon ? Heroicon::Plus : null)
            ->schema([
                ToolTextInput::make()
                    ->maxValue(fn($get) => Tool::find($record->id)?->qty ?? 0)
                    ->hint(function (callable $get) use ($record): ?string {
                        $toolId = $record->id;

                        if (!is_null($toolId)) {
                            $quantity = Tool::query()
                                ->whereKey($toolId)
                                ->value('qty');

                            return "Remaining quantity: {$quantity}";
                        }

                        return null;
                    }),
            ])
            ->action(function ($data) use ($record) {
                $data['tool_id'] = $record->id;
                Mouvement::CreateNewMouvement($data, LoanMouvement::class);

                //Create a notification to inform the user about the success of the operation
                Notification::make()
                    ->title('Tool taken')
                    ->body('The tool has been successfully taken.')
                    ->success()
                    ->send();
            });
    }
}

