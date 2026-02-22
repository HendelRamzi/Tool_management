<?php

namespace App\Filament\Resources\Mouvements\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\Mouvements\MouvementResource;
use App\Filament\Resources\Mouvements\Pages\Actions\InwardToolAction;
use App\Filament\Resources\Mouvements\Pages\Actions\ReturnToolAction;
use App\Filament\Resources\Mouvements\Pages\Actions\TakeToolAction;
use App\Models\LoanMouvement;
use App\Models\ReturnMouvement;
use Filament\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListMouvements extends ListRecords
{
    protected static string $resource = MouvementResource::class;


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
                ->label('Add new mouvement')
                ->color('info')
                ->button()
        ];
    }
}
