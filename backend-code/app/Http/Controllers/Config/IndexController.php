<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Model;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
      $modelId = $request->input('model_id');
      $currentPage = $request->input('page');
  
      $model = Model::find($modelId);
      $configs = $model->configs;
  
      // if ($configs->isEmpty()) {
      //   Config::create([
      //     'name' => 'My first config',
      //     'model_id' => $modelId,
      //     'confidence_score' => json_encode([
      //       'medium_max' => 60,
      //       'medium_min' => 40
      //     ]),
      //   ]);
      // }
  
      $configs = $model->configs()->orderBy('id', 'asc');
  
      $table = $configs->paginate(
        $perPage = 3, 
        $columns = ['*'],
        $pageName = 'configs',
        $page = $currentPage
      );
    
      return $table;
    }
}