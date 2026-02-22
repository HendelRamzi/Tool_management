<?php

namespace App\Filament\Resources\Tools\Pages;

use App\Enums\ToolStatus;
use App\Filament\Resources\Tools\ToolResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;


class ListTools extends ListRecords
{
    protected static string $resource = ToolResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'Disponible' => Tab::make()
                ->icon('heroicon-o-check-circle')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', ToolStatus::Disponible)),
            'No Disponible' => Tab::make()
                ->icon('heroicon-o-x-circle')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', ToolStatus::NoDisponible)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add new tool')
                ->icon(Heroicon::Plus)
                ->color('info'),
        ];
    }
}
