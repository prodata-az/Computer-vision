<?php

namespace App\Listeners;

use App\Events\CreateInferenceTriggerEvent;
use Illuminate\Support\Facades\DB;

class CreateTriggerListener
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
     * @param  \App\Events\CreateInferenceTriggerEvent $event
     * @return void
     */
    public function handle(CreateInferenceTriggerEvent $event)
    {
        DB::statement("
        CREATE OR REPLACE FUNCTION notify_new_log()
        RETURNS TRIGGER AS $$
        BEGIN
            IF (NEW.session_id = {$event->session_id}) THEN
                PERFORM pg_notify('new_log', NEW.id::text);
                RETURN NEW;
            END IF;
            RETURN NEW;
        END;
        $$ LANGUAGE plpgsql;
    ");

        DB::statement("
        CREATE TRIGGER new_log_trigger
        AFTER INSERT ON logs
        FOR EACH ROW
        EXECUTE PROCEDURE notify_new_log();
    ");
    }
}
