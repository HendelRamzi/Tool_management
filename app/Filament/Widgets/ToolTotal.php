<?php

namespace App\Filament\Widgets;

use App\Enums\ToolStatus;
use App\Enums\UserRole;
use App\Models\LoanMouvement;
use App\Models\Mouvement;
use App\Models\ReturnMouvement;
use App\Models\Tool;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ToolTotal extends StatsOverviewWidget
{

    public static function canView(): bool
    {
        return auth()->user()->hasRole(UserRole::super_admin);
    }


    protected function getStats(): array
    {
        return [
            Stat::make('Tools total', Tool::count())
                ->description('The total number')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),
            Stat::make('Disponible tools', Tool::where('status', ToolStatus::Disponible)->count())
                ->description('The disponible tools')
                ->descriptionIcon('heroicon-o-check-circle')
                ->chart([17, 10, 5, 3, 10, 12, 17])
                ->color('success'),
            Stat::make('Archived tools', Tool::where('status', ToolStatus::Archived)->count())
                ->description('Removed tools')
                ->descriptionIcon('heroicon-o-archive-box')
                ->chart([17, 40, 4, 20, 15, 4, 17])
                ->color('danger'),
            // Stat::make('Archived tools', Mouvement::where('mouvementable_type', LoanMouvement::class)->count()),
            // Stat::make('Returned mouvement', Mouvement::where('mouvementable_type', ReturnMouvement::class)->count()),
        ];
    }
}
