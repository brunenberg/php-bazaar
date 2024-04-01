<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserType
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
        if (auth()->check() && (auth()->user()->user_type === 'zakelijke_verkoper' || auth()->user()->user_type === 'particuliere_verkoper')) {
            return $next($request);
        }

        return redirect('home')->with('error', __('messages.no_access_to_page'));
    }
}
