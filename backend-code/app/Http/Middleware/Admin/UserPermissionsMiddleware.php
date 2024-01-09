<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class UserPermissionsMiddleware
{
    public function handle(Request $request, Closure $next, $route_name)
    {
      $user = auth()->user();

      if ($user->is_super_admin) {
        return $next($request);
      } else if ($user->admin) {
        $check = DB::table('user_permissions')
                 ->where(array('user_id' => $user->id, 'controller_name' => $route_name))
                 ->first();

        if ($check === null) {
          abort(403);
        } else {
          return $next($request);
        }
      } else {
        abort(403);
      }
    }
}
