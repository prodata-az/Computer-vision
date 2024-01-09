<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Models\Model;
use App\Models\User;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
  /**
   * Handle the incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function __invoke(Request $request, Model $model)
  {
    $userId = $request->input('user_id'); // get id of user
    $currentPage = $request->input('page'); // get current page;

    // delete model
    $model->delete();

    // find current user in the users table
    $user = User::find($userId);

    // find all models for a current user
    $models = $user->models()->orderBy('id', 'asc')->paginate(3, ['*'], 'models', $currentPage);

    $models = ModelResource::collection($models);

    return $models;
  }
}
