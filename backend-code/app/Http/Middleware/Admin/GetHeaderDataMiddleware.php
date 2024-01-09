<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class GetHeaderDataMiddleware
{
    public function handle(Request $request, Closure $next)
    {
      // $companies = $selected_company = $partners_is_active = $admin_modules_settings = $template = [];
      $companies = Company::all();

      $user = auth()->user();
      $selected_company = $user->selected_company_id;

      $permissions = DB::table('user_permissions')
                     ->where(array('user_id' => $user->id))
                     ->get();

      $admin_modules_settings = DB::table('admin_modules_settings')
                                ->where('company_id', $selected_company)
                                ->first();

      $partners_is_active = false;
      foreach ($companies as $row) {
        if ($row->id == $selected_company) {
          $partners_is_active = $row->partners_is_active;
        }
      }

      $template = DB::table('companies')
                  ->leftJoin('templates', 'templates.id', '=', 'companies.template_id')
                  ->select(DB::raw('templates.*'))
                  ->whereRaw('companies.id = '.$selected_company)
                  ->first();

      $data = array(
        'header' => array(
          'companies' => $companies,
          'selected_company' => $selected_company,
          'partners_is_active' => $partners_is_active,
          'admin_modules_settings' => $admin_modules_settings,
          'template' => $template,
          'permissions' => $permissions,
          'is_super_admin' => $user->is_super_admin
        )
      );

      $response = $next($request);
      $content = array('content' => $response->getData(true));

      return response()->json(array_merge($content, $data));
    }
}
