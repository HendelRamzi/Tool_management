<?php

namespace App\Filament\Widgets;


use App\Enums\UserRole;
use App\Models\LoanMouvement;
use App\Models\Mouvement;
use App\Models\ReturnMouvement;
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
        return [
            Stat::make('Tools Mouvement', Mouvement::count())
                ->description('All the mouvement')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),
            // Stat::make('Archived tools', Mouvement::where('mouvementable_type', LoanMouvement::class)->count()),
            // Stat::make('Returned mouvement', Mouvement::where('mouvementable_type', ReturnMouvement::class)->count()),
        ];
    }
}