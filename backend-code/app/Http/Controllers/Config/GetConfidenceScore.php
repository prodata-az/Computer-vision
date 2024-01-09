<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Config;

class GetConfidenceScore extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Config $config)
    {
        $confidenceScore = $config->confidence_score;
        return json_decode($confidenceScore);
    }
}
