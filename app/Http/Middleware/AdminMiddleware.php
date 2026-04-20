<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Closure - anonymous function or functions without a name that you can use immediately
        //$next - proceeds to next request
        if(Auth::check() && Auth::user()->role_id == User::ADMIN_ROLE_ID){
            return $next($request);
        }
        return redirect()->route('index');
    }
}
