<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
use Filament\Actions\RestoreAction;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;


    public function getTitle(): string
    {
        return $this->record->full_name;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->icon(Heroicon::PencilSquare)
                 ->hidden(fn() => ! is_null($this->record->deleted_at)),

            RestoreAction::make('Restore')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Tool restored')
                        ->body('The tool has been restored successfully.'),
                )
                ->icon(Heroicon::ArrowPath)
        ];
    }
}
