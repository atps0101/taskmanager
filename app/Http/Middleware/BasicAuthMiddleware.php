<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $username = $request->getUser();
        $password = $request->getPassword();

        if ($username && $password) {

            $user = (new User)->findForBasicAuthentication($username);

            if ($user && \Hash::check($password, $user->password)) {
                Auth::login($user);
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

}
