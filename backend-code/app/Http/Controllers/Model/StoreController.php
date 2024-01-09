<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Models\Config;
use App\Models\Model;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
  public function __invoke(Request $request)
  {
    $userId = $request->input('user_id');
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
      'name' => ['string', 'required', Rule::unique('models')->where(function ($query) use ($request) {
        return $query->where('user_id', $request->user_id);
      })],
      'user_id' => 'integer'
    ]);

    // add new model
    Model::create($data);

    $user = User::find($userId);
    $models = $user->models;
    $model = $models->where('name', $name)->first();
    $modelId = $model->id;

    Config::create([
      'model_id' => $modelId,
      'confidence_score' => json_encode([
        'medium_max' => 60,
        'medium_min' => 40
      ]),
    ]);

    // find all models for a current user
    $models = $user->models()->orderBy('id', 'asc')->paginate(3, ['*'], 'models', $currentPage);

    $models = ModelResource::collection($models);

    return $models;
  }
}
