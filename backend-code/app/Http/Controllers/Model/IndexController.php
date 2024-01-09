<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Models\Config;
use App\Models\Model;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
  public function __invoke(Request $request)
  {
    $userId = $request->input('user_id');
    $currentPage = $request->input('page');

    $user = User::find($userId);
    $models = $user->models;

    if ($models->isEmpty()) {
      Model::create([
        'name' => 'My first model',
        'user_id' => $userId,
        'inference' => false
      ]);

      $user = User::find($userId);
      $models = $user->models;
      $model = $models->where('name', 'My first model')->first();
      $modelId = $model->id;

      Config::create([
        'model_id' => $modelId,
        'confidence_score' => json_encode([
          'medium_max' => 60,
          'medium_min' => 40
        ]),
      ]);
    }

    $models = $user->models()->orderBy('id', 'asc')->paginate(3, ['*'], 'models', $currentPage);

    $models = ModelResource::collection($models);

    return $models;
  }
}
