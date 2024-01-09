<?php

namespace App\Http\Controllers\Train;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class GetLabelsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Config $config)
    {
        $labels = $config->labels;
        $result = [];

        foreach ($labels as $label) {
            $result[] = [$label->label_0, $label->label_1, $label->id,];
        }

        return $result;
    }
}
