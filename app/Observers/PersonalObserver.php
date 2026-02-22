<?php

namespace App\Observers;

use App\Models\Personal;
use App\Models\User;

class PersonalObserver
{
    /**
     * Handle the Personal "created" event.
     */
    public function created(Personal $personal): void
    {   
        User::GenerateUserName($personal->full_name, $personal->personal); 
    }

    /**
     * Handle the Personal "updated" event.
     */
    public function updated(Personal $personal): void
    {
        //
    }

    /**
     * Handle the Personal "deleted" event.
     */
    public function deleted(Personal $personal): void
    {
        //
    }

    /**
     * Handle the Personal "restored" event.
     */
    public function restored(Personal $personal): void
    {
        //
    }

    /**
     * Handle the Personal "force deleted" event.
     */
    public function forceDeleted(Personal $personal): void
    {
        //
    }
}
