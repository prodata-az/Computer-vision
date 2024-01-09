<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Label;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Label $label)
    {
        $configId = $request->input('config_id');
        $currentPage = $request->input('page');
  
        $label->delete();
    
        $config = Config::find($configId);
        $labels = $config->labels()->orderBy('id', 'asc');
    
        // get new table 'models' of current user
        $table = $labels->paginate(
            $perPage = 3, 
            $columns = ['*'],
            $pageName = 'labels',
            $page = $currentPage
        );
    
        // return new table
        return $table;
    }
}
