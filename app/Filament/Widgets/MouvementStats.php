<?php

namespace App\Filament\Widgets;


use App\Enums\UserRole;
use App\Models\Mouvement;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MouvementStats extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        return true; 
    }


    protected function getStats(): array
    {
        $MouvementTotal = auth()->user()->hasRole(UserRole::super_admin) ? Mouvement::count() : Mouvement::where('user_id', auth()->user()->id)->count(); 
        return [
            Stat::make(__("Tools Mouvement"), $MouvementTotal)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),
            // Stat::make('Archived tools', Mouvement::where('mouvementable_type', LoanMouvement::class)->count()),
            // Stat::make('Returned mouvement', Mouvement::where('mouvementable_type', ReturnMouvement::class)->count()),
        ];
    }
}