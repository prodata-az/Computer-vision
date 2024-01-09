<?php

namespace App\Http\Controllers\Train;

use App\Http\Controllers\Controller;
use App\Models\Hard_label;
use App\Models\Model;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $modelId = $request->input('model_id');
        $data = $request->input('data');
        $data = json_decode($data, true);
        $data_for_train = ['model_id' => $modelId];
        $url = 'http://10.20.36.26:8000/train/';

        foreach ($data as $key => $value) {
            $videoId = $key;
            $labels = $value;
            $labelSelected = false;
    
            foreach ($labels as $key => $value) {
                $value = (array) $value;
                if ($value['is_hard_label'] === true) {
                    $labelSelected = true;
                    break;
                }
            }

            if ($labelSelected) {
                Hard_label::create([
                    'video_id' => $videoId,
                    'model_id' => $modelId,
                    'labels' => json_encode($labels),
                ]);
            }
        };

        $model = Model::find($modelId);
        $model->update(['inference' => 1]);

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data_for_train),
            ),
        ); 
        $context  = stream_context_create($options);
        file_get_contents($url, false, $context);
    }
}
