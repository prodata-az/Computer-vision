<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Models\User;
use Illuminate\Http\Request;

class AllListController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(User $user)
    {
        $models = $user->models->sortBy('id');
        return ModelResource::collection($models);
    }
}
