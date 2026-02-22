<?php

namespace App\Filament\Resources\Tools\Pages;

use App\Enums\ToolStatus;
use App\Filament\Resources\Tools\Actions\ToolDeleteAction;
use App\Filament\Resources\Tools\ToolResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\Size;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;
class ViewTool extends ViewRecord
{
    protected static string $resource = ToolResource::class;

    public function getTitle(): string
    {
        return $this->record->name . " (" . $this->record->reference . ")";
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->icon(Heroicon::PencilSquare)
                ->visible(fn() => $this->record->status !== ToolStatus::Archived),
            ToolDeleteAction::make(true),
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
