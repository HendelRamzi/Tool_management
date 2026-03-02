<?php

namespace App\Filament\Resources\Mouvements\Pages\Actions;

use Filament\Actions\Action;

class CostumViewAction
{
    public static function make($title, $icon = null)
    {
        return Action::make($title)
            ->icon($icon)
            ->openUrlInNewTab(false);
    }
}