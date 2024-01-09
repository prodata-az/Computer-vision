<?php

namespace App\Listeners;

use App\Events\RemoveInferenceTriggerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class RemoveTriggerListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\RemoveInferenceTriggerEvent  $event
     * @return void
     */
    public function handle(RemoveInferenceTriggerEvent $event)
    {
            DB::statement("
            DROP TRIGGER IF EXISTS new_log_trigger ON logs;
        ");

            DB::statement("
            DROP FUNCTION IF EXISTS notify_new_log();
        ");
    }
}
