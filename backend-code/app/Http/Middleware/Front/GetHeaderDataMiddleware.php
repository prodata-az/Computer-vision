<?php

namespace App\Http\Middleware\Front;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetHeaderDataMiddleware
{
    public function handle(Request $request, Closure $next)
    {
      $domain = $request->domain;

      $companies = DB::table('companies')
                   ->select('id')
                   ->where('domain', $domain)
                   ->first();

      $contacts = DB::table('contacts')
                  ->where('company_id', $companies->id)
                  ->get();

      $contact_settings = DB::table('contact_settings')
                          ->where('company_id', $companies->id)
                          ->first();

      $data = array(
        'contacts' => $contacts,
        'contact_settings' => $contact_settings
      );

      $response = $next($request);
      $content = $response->getData(true);

      return response()->json(array_merge($content, $data));
    }
}
