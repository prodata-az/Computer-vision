<?php

namespace App\Http\Controllers\Log;

use App\Events\NewLogEvent;
use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\Request;

class GetNewLogController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param string $payload
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id)
    {
        $log = Log::find($id);
        $response = $log->response;
        broadcast(new NewLogEvent(json_decode($response)))->toOthers();
    }
}
