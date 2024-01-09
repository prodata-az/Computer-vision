<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Label;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

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
        $configId = $request->input('config_id');
        $currentPage = $request->input('page');
        $label_0 = $request->input('label_0');
        $label_1 = $request->input('label_1');
    
        if (is_numeric($label_0)) {
            $request->merge(['label_0' => (int) $label_0]);
        } else if (is_numeric($label_1)) {
            $request->merge(['label_1' => (int) $label_1]);
        } else {
          $label_0 = mb_strtolower($label_0);
          $label_0 = ucfirst($label_0);
          $request->merge(['label_0' => $label_0]);

          $label_1 = mb_strtolower($label_1);
          $label_1 = ucfirst($label_1);
          $request->merge(['label_1' => $label_1]);
        }
        
        $data = $request->validate([
            'label_0' => ['string', 'required', Rule::unique('labels')->where(function ($query) use ($request) {
                return $query->where('config_id', $request->config_id);
            })],
            'label_1' => ['string', 'required', Rule::unique('labels')->where(function ($query) use ($request) {
            return $query->where('config_id', $request->config_id);
            })],
            'config_id' => 'integer',
        ]);
        
        Label::create($data);

        $config = Config::find($configId);
        $labels = $config->labels()->orderBy('id', 'asc');
    
        $table = $labels->paginate(
          $perPage = 3, 
          $columns = ['*'],
          $pageName = 'labels',
          $page = $currentPage
        );
      
        return $table;
    }
}
