<?php

namespace App\Observers;

use App\Enums\ToolStatus;
use App\Models\Tool;

class ToolObserver
{
    /**
     * Handle the Tools "created" event.
     */
    public function created(Tool $tool): void
    {
        //
    }

    /**
     * Handle the Tools "updated" event.
     */
    public function updated(Tool $tool): void
    {
    }

    /**
     * Handle the Tools "deleted" event.
     */
    public function deleted(Tool $tool): void
    {
        // Make the tool desable when soft delete
        $tool->status = ToolStatus::Archived;
        $tool->save();
    }

    /**
     * Handle the Tools "restored" event.
     */
    public function restored(Tool $tool): void
    {
        $tool->status = $tool->available_quantity > 0 ? ToolStatus::Disponible : ToolStatus::NoDisponible;
        $tool->save();
    }

    /**
     * Handle the Tools "force deleted" event.
     */
    public function forceDeleted(Tool $tool): void
    {
        //
    }
}
