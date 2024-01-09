<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Model;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Config $config)
    {
      $modelId = $request->input('model_id');
      $configId = $request->input('model_id');
      $currentPage = $request->input('page');

      $config->delete();
  
      $configs = Model::find($modelId)->configs()->orderBy('id', 'asc');
  
      // get new table 'models' of current user
      $table = $configs->paginate(
          $perPage = 3, 
          $columns = ['*'],
          $pageName = 'configs',
          $page = $currentPage
      );
  
      // return new table
      return $table;
    }
}
