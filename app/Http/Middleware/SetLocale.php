<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SetLocale {
    public function handle($request, Closure $next) {
        if (auth()->check()) {
            $locale = Auth::user()->language;
        } else {
            // Get the locale from the session
            $locale = session('locale', 'nl');
        }
        app()->setLocale($locale);
        return $next($request);
    }
}
