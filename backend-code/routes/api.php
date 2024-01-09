<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
  return $request->user();
});

// Models
Route::group(
  [
    'prefix' => 'models',
    'namespace' => 'App\Http\Controllers\Model'
  ],
  function () {
    Route::get('/', 'IndexController');
    Route::get('/{user}', 'AllListController');
    Route::post('/store', 'StoreController');
    Route::patch('/{model}/name', 'UpdateController');
    Route::delete('/{model}', 'DestroyController');
    Route::post('/status', 'ChangeStatusController');
    Route::post('/active', 'GetActiveController');
  }
);

// Configs
Route::group(
  [
    'prefix' => 'configs',
    'namespace' => 'App\Http\Controllers\Config'
  ],
  function () {
    Route::get('/', 'IndexController');
    Route::post('/store', 'StoreController');
    Route::patch('/{config}/name', 'UpdateController');
    Route::delete('/{config}', 'DestroyController');
    Route::get('/{config}/confidence_score', 'GetConfidenceScore');
    Route::patch('/{config}/confidence_score', 'SetConfidenceScore');
  }
);

// Labels
Route::group(
  [
    'prefix' => 'labels',
    'namespace' => 'App\Http\Controllers\Label'
  ],
  function () {
    Route::get('/', 'IndexController');
    Route::post('/store', 'StoreController');
    Route::patch('/{label}/name', 'UpdateController');
    Route::delete('/{label}', 'DestroyController');
  }
);

// Train
Route::group(
  [
    'prefix' => 'train',
    'namespace' => 'App\Http\Controllers\Train'
  ],
  function () {
    Route::get('/{config}', 'GetLabelsController');
    Route::post('/', 'IndexController');
    Route::post('/run', 'TrainController');
  }
);

// Inference
Route::group(
  [
    'prefix' => 'inference',
    'namespace' => 'App\Http\Controllers\Inference'
  ],
  function () {
    Route::post('/', 'IndexController');
    Route::post('/get_hard_labels', 'GetHardLabelsController');
    Route::get('/logs/{log}', 'LogsController');
    Route::get('/{config}', 'GetLabelsController');
  }
);

// Logs
Route::group(
  [
    'prefix' => 'logs',
    'namespace' => 'App\Http\Controllers\Log'
  ],
  function () {
    Route::get('/', 'RunInferenceController');
  }
);