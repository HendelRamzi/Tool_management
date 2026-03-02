<?php

namespace App\Filament\Resources\Tools\Schemas;

use App\Enums\ToolStatus;
use App\Filament\Resources\Tools\ToolResource;
use App\Models\Mouvement;
use App\Models\ReturnMouvement;
use App\Services\MouvementService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\Text;
class ToolInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Callout::make('Returned tool incomplete')
                ->visible(fn($record) => MouvementService::remainingQuantity($record->id, auth()->user()->id) > 0)
                ->description(fn($record) => 'You still have to return ' . MouvementService::remainingQuantity($record->id, auth()->user()->id) . ' more ' . $record->name)
                ->columnSpanFull()
                ->footer([
                    Text::make('Last taken: 5 minutes ago')
                        ->color('gray'),
                    Action::make('viewBackups')
                        ->color('gray')
                        ->label('Return tool')
                        ->action(function ($record) {
                            Mouvement::CreateNewMouvement([
                                "tool_id" => $record->id,
                                "qty" => MouvementService::remainingQuantity($record->id, auth()->user()->id)
                            ], ReturnMouvement::class);

                            // Create a notification to inform the user about the success of the operation
                            Notification::make()
                                ->title('Tool returned')
                                ->body('The tool has been successfully returned.')
                                ->success()
                                ->send();
                        })->successRedirectUrl(fn($record) => ToolResource::getUrl('view', ['record' => $record]))
                        ->button(),
                ])
                ->warning(),
            Section::make('Tool Information')
                ->description('Detailed information about the tool')
                ->columnSpanFull()
                ->collapsible()
                ->columns(3)
                ->schema([
                    TextEntry::make('name')
                        ->copyable()
                        ->label('Tool name'),
                    TextEntry::make('reference')
                        ->copyable()
                        ->weight(FontWeight::Bold)
                        ->columnSpan(2)
                        ->label('Tool reference'),
                    TextEntry::make('status')
                        ->weight(FontWeight::Bold)
                        ->size(TextSize::Large)
                        ->badge()
                        ->icon(fn(string $state) => match ($state) {
                            ToolStatus::Disponible => 'heroicon-o-check-circle',
                            ToolStatus::NoDisponible => 'heroicon-o-x-circle',
                            ToolStatus::NoFunctionnal => 'heroicon-o-x-circle',
                            ToolStatus::Archived => 'heroicon-o-archive-box',

                        })
                        ->color(fn(string $state): string => match ($state) {
                            ToolStatus::Disponible => 'success',
                            ToolStatus::NoDisponible => 'danger',
                            ToolStatus::NoFunctionnal => 'danger',
                            ToolStatus::Archived => 'warning',

                        }),
                    TextEntry::make('qty')
                        ->label('Quantity')
                        ->size(TextSize::Large)
                        ->badge()
                        ->color("info"),
                    TextEntry::make('created_at')
                        ->date()
                ]),
        ]);
    }
}