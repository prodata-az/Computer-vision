<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class SSOController extends Controller
{
    public function getLogin(Request $request)
    {
        if ($request->has(['path'])) {
          $request->session()->put("path_params", $request->all());
        }

        $request->session()->put("state", $state =  Str::random(40));
        $query = http_build_query([
            "client_id" => config("auth.client_id"),
            "redirect_uri" => config("auth.callback"),
            "response_type" => "code",
            "scope" => config("auth.scopes"),
            "state" => $state
        ]);

        return redirect(config("auth.sso_host") .  "/oauth/authorize?" . $query);
    }

    public function getCallback(Request $request)
    {
        $state = $request->session()->pull("state");

        throw_unless(strlen($state) > 0 && $state == $request->state, InvalidArgumentException::class);

        $code = $request->code;

        $response = Http::withoutVerifying()->asForm()->post(
            config("auth.sso_host") .  "/oauth/token",
            [
                "grant_type" => "authorization_code",
                "client_id" => config("auth.client_id"),
                "client_secret" => config("auth.client_secret"),
                "redirect_uri" => config("auth.callback") ,
                "code" => $code
            ]
        );
        $session_array = $response->json();

        foreach ($session_array as $key => $value) {
          $request->session()->put($key, $value);
        }

        return redirect(route("sso.connect"));
    }

    public function connectUser(Request $request)
    {
        $access_token = $request->session()->get("access_token");
        $response = Http::withoutVerifying()->withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer " . $access_token
        ])->get(config("auth.sso_host") .  "/api/user");

        $userArray = $response->json();

        try {
            $email = $userArray['email'];
        } catch (\Throwable $th) {
            return redirect("login")->withError("Failed to get login information! Try again.");
        }
        $user = User::where("email", $email)->first();
        if (!$user) {
            $user = new User;
            $user->name = $userArray['name'];
            $user->email = $userArray['email'];
            $user->email_verified_at = $userArray['email_verified_at'];
            $user->save();
        }
        Auth::login($user);

        $redirect_path = config("auth.frontend_path");
        if ($request->session()->has('path_params')) {
          $path = '';
          $query_params = '';
          $path_params = $request->session()->get('path_params');
          foreach ($path_params as $key => $value) {
            if ($key == 'path') {
              $path = $value;
            } else {
              $query_params = $query_params . '&' . $key . '=' . $value;
            }
          }
          $redirect_path = $redirect_path . $path . '?' . $query_params;
        }
        return redirect($redirect_path);
    }

    public function getUser(Request $request)
    {
        $access_token = $request->session()->get("access_token");
        $response = Http::withoutVerifying()->withHeaders([
            "Accept" => "application/json",
            "Authorization" => "Bearer " . $access_token
        ])->get(config("auth.sso_host") .  "/api/user");

        $userArray = $response->json();

        try {
            $email = $userArray['email'];
        } catch (\Throwable $th) {
            abort(401);
        }

        return response()->json($userArray);
    }
}
