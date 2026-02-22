<?php

namespace App\Filament\Resources\Mouvements\Pages\Actions;

use App\Filament\Resources\Tools\Pages\EditTool;
use App\Models\InwardMouvement;
use App\Models\Mouvement;
use App\Models\Tool;
use App\Services\MouvementService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class InwardToolAction
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

    public static function make($record = null)
    {
        return Action::make('Add a tool to stock')
            ->schema([
                ToolSelectInput::make(self::getTools(), 'Select the tool to add to stock')
                    ->hidden(fn($livewire) => $livewire instanceof EditTool),
                ToolTextInput::make("Enter the quantity to add to stock")
                    ->hint(fn($get) => $get('tool_id') ? 'Remaining quantity: ' . Tool::find($get('tool_id'))?->qty ?? 0 : null)
                    ->belowContent("The entered quantity will be added to the current stock quantity of the selected tool."),
            ])
            ->action(function ($data) use ($record) {
                if ($record) {
                    $data["tool_id"] = $record->id;
                }
                Mouvement::CreateNewMouvement($data, InwardMouvement::class);

                //Create a notification to inform the user about the success of the operation
                Notification::make()
                    ->title('Tool added to stock')
                    ->body('The tool has been successfully added to stock.')
                    ->success()
                    ->send();
            })
            ->color('info')
            ->icon(Heroicon::Plus)
            ->requiresConfirmation();
    }
}