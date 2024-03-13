<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SetLocale {
    public function handle($request, Closure $next) {
        if (Auth::check()) {
            $locale = Auth::user()->language;
        } else {
            $locale = 'nl';
        }
        app()->setLocale($locale);
        return $next($request);
    }
}
