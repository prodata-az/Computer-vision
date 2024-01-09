<?php

namespace App\Http\Controllers\Inference;

use App\Events\CreateInferenceTriggerEvent;
use App\Events\RemoveInferenceTriggerEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetHardLabelsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $model_id = $request->input('model_id');
        $video_ids = $request->input('video_ids');
        $session_id = $request->input('session_id');
        $data = ['model_id' => $model_id, 'video_ids' => $video_ids, 'session_id' => $session_id];

        event(new RemoveInferenceTriggerEvent);
        event(new CreateInferenceTriggerEvent($session_id));

        $url = 'http://10.20.36.26:8000/inference';

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ),
        ); 
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return json_decode($result);
    }
}
