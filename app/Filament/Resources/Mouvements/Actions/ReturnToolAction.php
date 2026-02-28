<?php

namespace App\Filament\Resources\Mouvements\Pages\Actions;

use App\Models\LoanMouvement;
use App\Models\Mouvement;
use App\Models\ReturnMouvement;
use App\Services\MouvementService;
use App\Services\StockService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class ReturnToolAction
{

    public static function getTools(): array
    {
        return LoanMouvement::query()
            ->where('user_id', auth()->id())
            ->where('remaining_quantity', '>', 0)->with('tool')
            ->get()->mapWithKeys(fn($loan) => [$loan->tool->id => "{$loan->tool->name} - {$loan->tool->reference}"])
            ->toArray();
    }

    public static function remainingQuantity(int $toolId, int $userId): int
    {
        return LoanMouvement::where('tool_id', $toolId)
            ->where('user_id', $userId)
            ->sum('remaining_quantity');
    }

    public static function form()
    {
        return [
            ToolSelectInput::make(self::getTools(), 'Select the tool to return'),

            ToolTextInput::make('quantity')
                ->hint(function ($get) {
                    if (!$get('tool_id')) {
                        return null;
                    }

                    $remaining = self::remainingQuantity(
                        $get('tool_id'),
                        auth()->id()
                    );

                    return "Remaining quantity: {$remaining}";
                })
                ->maxValue(function ($get) {
                    if (!$get('tool_id')) {
                        return null;
                    }

                    return self::remainingQuantity($get('tool_id'), auth()->id());
                }),
        ];
    }

    public static function make()
    {
        return Action::make('Return a tool')
            ->schema(self::form())
            ->action(function ($data) {
                StockService::returnTool($data['tool_id'], $data['qty']);

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