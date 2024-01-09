<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class SetConfidenceScore extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Config $config)
    {
        $min = $request->input('min');
        $max = $request->input('max');

        $data = json_encode(['medium_max' => $max, 'medium_min' => $min]);
        $config->update(['confidence_score' => $data]);
    }
}
