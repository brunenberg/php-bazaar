<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NormalUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', __('messages.log_in_to_access_page'));
        }
        if ($request->user() && $request->user()->user_type !== 'gebruiker') {
            return redirect()->route('home')->with('error', __('messages.not_authorized_to_access_page'));
        }
        return $next($request);
    }
}
