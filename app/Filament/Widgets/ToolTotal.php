<?php

namespace App\Filament\Widgets;

use App\Enums\ToolStatus;
use App\Enums\UserRole;
use App\Models\Tool;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ToolTotal extends StatsOverviewWidget
{

    // protected ?string $heading = __('Tools Overview');

    protected function getHeading(): string
    {
        return __('Tools Overview'); // clé de traduction
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole(UserRole::super_admin);
    }


    protected function getStats(): array
    {
        return [
            Stat::make(__('Total Tools'), Tool::count())
                ->chart([17, 10, 5, 3, 10, 12, 17])
                ->color('gray'),
            Stat::make(__('Loan Stock Tools'), Tool::where('qty', "<=", 5)->count())
                ->chart([17, 10, 5, 3, 10, 12, 17])
                ->color('danger'),
            Stat::make(__('Out of stock tools'), Tool::where('qty', "<=", 0)->count())
                ->chart([17, 40, 4, 20, 15, 4, 17])
                ->color('warning'),
            Stat::make(__('Archived Tools'), Tool::where('status', ToolStatus::Archived)->count())
                ->chart([17, 40, 4, 20, 15, 4, 17])
                ->color('info'),
            // Stat::make('Archived tools', Mouvement::where('mouvementable_type', LoanMouvement::class)->count()),
            // Stat::make('Returned mouvement', Mouvement::where('mouvementable_type', ReturnMouvement::class)->count()),
        ];
    }
}
