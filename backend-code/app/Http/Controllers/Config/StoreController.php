<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreController extends Controller
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
      $currentPage = $request->input('page');
      $name = $request->input('name');
  
      if (is_numeric($name)) {
        $request->merge(['name' => (int) $name]);
      } else {
        $name = mb_strtolower($name);
        $name = ucfirst($name);
        $request->merge(['name' => $name]);
      }
      
      // validate
      $data = $request->validate([
        'name' => ['string', 'required', Rule::unique('configs')->where(function ($query) use ($request) {
          return $query->where('model_id', $request->model_id);
        })],
        'model_id' => 'integer',
        'confidence_score' => 'json'
      ]);
      
      // add new config
      Config::create($data);
  
      $configs = Model::find($modelId)->configs()->orderBy('id', 'asc');
  
      $table = $configs->paginate(
        $perPage = 3, 
        $columns = ['*'],
        $pageName = 'configs',
        $page = $currentPage
      );
    
      // return table
      return $table;
    }
}
