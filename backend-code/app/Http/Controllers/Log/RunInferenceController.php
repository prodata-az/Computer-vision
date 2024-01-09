<?php

namespace App\Http\Controllers\Log;

use App\Events\CreateInferenceTriggerEvent;
use App\Events\RemoveInferenceTriggerEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RunInferenceController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $session_id = $request->input('session_id');

        // event(new RemoveInferenceTriggerEvent);
        // event(new CreateInferenceTriggerEvent($session_id));
    }
}