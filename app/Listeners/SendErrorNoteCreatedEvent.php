<?php

namespace App\Listeners;

use App\Events\ErrorNoteCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendErrorNoteCreatedEvent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ErrorNoteCreated $event): void
    {
        //When an error note is created, change the File status to 'error'
        $file = $event->note->notable; 
        if(!is_null($file)){
            $file->status = 'error';
            $file->save();
        }
    }
}
