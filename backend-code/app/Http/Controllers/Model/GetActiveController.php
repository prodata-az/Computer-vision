<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GetActiveController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
      $userId = $request->input('user_id');

      $user = User::find($userId);
      $models = $user->models;
  
      $model = $models->where('status', true);  
  
      return $model;
    }
}
