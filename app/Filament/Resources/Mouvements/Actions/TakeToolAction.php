<?php

namespace App\Filament\Resources\Mouvements\Pages\Actions;

use App\Models\LoanMouvement;
use App\Models\Mouvement;
use App\Models\Tool;
use App\Services\StockService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class TakeToolAction
{

    public static function getTools()
    {
        return Tool::query()
            ->select('id', 'name', 'reference')
            ->orderBy('name')
            ->get()
            ->mapWithKeys(fn($tool) => [
                $tool->id => "{$tool->name} - {$tool->reference}",
            ])
            ->toArray();
    }

    public static function form()
    {
        return [
            ToolSelectInput::make(self::getTools(), 'Select the tool to take'),
            ToolTextInput::make("Enter the quantity to take")
                ->maxValue(fn($get) => Tool::find($get('tool_id'))?->available_quantity ?? 0)
                ->hint(function (callable $get): ?string {
                    $toolId = $get('tool_id');

                    if (!is_null($toolId)) {
                        $quantity = Tool::query()
                            ->whereKey($toolId)
                            ->value('available_quantity');

                        return "Remaining quantity: {$quantity}";
                    }

                    return null;
                }),
        ];
    }

    public static function make()
    {
        return Action::make('Take a tool')
            ->schema(self::form())
            ->action(function ($data) {
                StockService::takeTool($data['tool_id'], $data['qty']);

                //Create a notification to inform the user about the success of the operation
                Notification::make()
                    ->title('Tool taken')
                    ->body('The tool has been successfully taken.')
                    ->success()
                    ->send();
            })
            ->color('success')
            ->icon(Heroicon::ArrowUp)
            ->requiresConfirmation();
    }

}
