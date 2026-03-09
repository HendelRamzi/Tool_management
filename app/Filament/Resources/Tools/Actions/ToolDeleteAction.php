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
            ->label(__('Delete'))
            ->requiresConfirmation()
            ->icon($withIcon ? Heroicon::Trash : null)
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title(__('Tool deleted'))
                    ->body(__('The tool has been deleted successfully.')),
            );
    }
}

