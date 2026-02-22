<?php

namespace App\Filament\Resources\Tools\Pages;

use App\Filament\Resources\Tools\ToolResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
class CreateTool extends CreateRecord
{
    protected static string $resource = ToolResource::class;


    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Tool created !')
            ->body('The tool has been created successfully.');
    }
}
