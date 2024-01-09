<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Models\Model;
use App\Models\User;
use Illuminate\Http\Request;

class ChangeStatusController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
      $id = $request->input('id');
      $userID = $request->input('user_id');
  
      $user = User::find($userID);
      $models = $user->models;
  
      $model = $models->where('status', true)->first();
      $model->update(['status' => false]);
  
      Model::find($id)->update(['status' => true]);
    }
}
