<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Label;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $configId = $request->input('config_id');
        $currentPage = $request->input('page');

        $configs = Config::find($configId);
        $labels = $configs->labels;

        $labels = $configs->labels()->orderBy('id', 'asc');

        $table = $labels->paginate(
            $perPage = 3, 
            $columns = ['*'],
            $pageName = 'labels',
            $page = $currentPage
        );

        return $table;
    }
}
