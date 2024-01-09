<?php

use App\Http\Controllers\FFMpeg\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SSO\SSOController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return ['Laravel' => app()->version()];
});

require __DIR__ . '/auth.php';

Auth::routes(['register' => false, 'login' => false, 'reset' => false]);

Route::get("/sso/login", [SSOController::class, 'getLogin'])->name("sso.login");
Route::get("/callback", [SSOController::class, 'getCallback'])->name("sso.callback");
Route::get("/sso/connect", [SSOController::class, 'connectUser'])->name("sso.connect");
Route::get("/user", [SSOController::class, 'getUser'])->name("sso.user");
