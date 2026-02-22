<?php

namespace App\Filament\Resources\Tools\Pages;

use App\Filament\Resources\Mouvements\Pages\Actions\InwardToolAction;
use App\Filament\Resources\Tools\Actions\ToolDeleteAction;
use App\Filament\Resources\Tools\ToolResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
class EditTool extends EditRecord
{
    protected static string $resource = ToolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ToolDeleteAction::make(true),
            InwardToolAction::make($this->record)
        ];
    }


    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Tool updated')
            ->body('The tool has been updated and saved successfully.');
    }
}
