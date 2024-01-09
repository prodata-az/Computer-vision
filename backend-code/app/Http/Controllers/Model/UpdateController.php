<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Models\Model;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateController extends Controller
{
  public function __invoke(Request $request, Model $model)
  {
    $userId = $request->input('user_id'); // get id of user
    $currentPage = $request->input('page'); // get current page;
    $name = $request->input('name');
    $user = User::find($userId);

    if (is_numeric($name)) {
      $request->merge(['name' => (int) $name]);
    } else {
      $name = mb_strtolower($name);
      $name = ucfirst($name);
      $request->merge(['name' => $name]);
    }

    // // validate
    $data = $request->validate([
      'name' => [
        'string', 'required', Rule::unique('models')->where(function ($query) use ($request) {
          return $query->where('user_id', $request->user_id);
        })
      ],
    ]);

    // update the model name
    $model->update(['name' => $data['name']]);

    // find all models for a current user
    $models = $user->models()->orderBy('id', 'asc')->paginate(3, ['*'], 'models', $currentPage);

    $models = ModelResource::collection($models);

    return $models;
  }
}
