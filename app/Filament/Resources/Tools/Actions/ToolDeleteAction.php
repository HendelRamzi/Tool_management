<?php

namespace App\Filament\Resources\Tools\Actions;

use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class ToolDeleteAction
{
    public static function make($withIcon = false)
    {
        return DeleteAction::make()
            ->label('Delete')
            ->requiresConfirmation()
            ->icon($withIcon ? Heroicon::Trash : null)
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title('Tool deleted')
                    ->body('The tool has been deleted successfully.'),
            );
    }
}

