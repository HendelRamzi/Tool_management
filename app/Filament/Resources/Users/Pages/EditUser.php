<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('User updated'))
            ->body(__('The user has been updated and saved successfully.'));
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label(__('Delete'))
                ->requiresConfirmation()
                ->icon(Heroicon::Trash)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('User deleted'))
                        ->body(__('The user has been deleted successfully')),
                )
        ];
    }
}
