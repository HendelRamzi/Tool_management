<?php

namespace App\Filament\Resources\Mouvements\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\Mouvements\MouvementResource;
use App\Filament\Resources\Mouvements\Pages\Actions\InwardToolAction;
use App\Filament\Resources\Mouvements\Pages\Actions\ReturnToolAction;
use App\Filament\Resources\Mouvements\Pages\Actions\TakeToolAction;
use App\Models\LoanMouvement;
use App\Models\Mouvement;
use App\Models\ReturnMouvement;
use App\Services\MouvementService;
use Filament\Actions\ActionGroup;
use Filament\Schemas\Components\Callout;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Schema;


class ListMouvements extends ListRecords
{
    protected static string $resource = MouvementResource::class;

    public function getTitle(): string
    {
        return __('Mouvements');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Callout::make(__('Returned Tool Incomplete'))
                    ->visible(fn($record) => MouvementService::userHasLoans(auth()->user()->id))
                    ->description(fn($record) => __('You still have some tools that need to be returned'))
                    ->danger(),
                $this->getTabsContentComponent(), // This method returns a component to display the tabs above a table
                RenderHook::make(PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE),
                EmbeddedTable::make(), // This is the component that renders the table that is defined in this resource
                RenderHook::make(PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER),
            ]);
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'Taken' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('mouvementable_type', LoanMouvement::class)),
            'Returned' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('mouvementable_type', ReturnMouvement::class)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                TakeToolAction::make(),
                ReturnToolAction::make(),
                InwardToolAction::make()
                    ->visible(auth()->user()->hasRole(UserRole::super_admin)),
            ])
                ->label(__('Añadir nuevo movimiento'))
                ->color('info')
                ->icon(Heroicon::ChevronDown)
                ->button()
        ];
    }
}
