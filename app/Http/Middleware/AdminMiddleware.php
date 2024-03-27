<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You need to be logged in to access this page.');
        }
        if ($request->user() && $request->user()->user_type !== 'admin') {
            return redirect()->route('home')->with('error', 'You are not authorized to access this page.');
        }
        return $next($request);
    }
}
