<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user has already selected a language
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        } else {
            // Always default to Arabic (ar) unless the user selects another language
            $locale = 'ar';
            Session::put('locale', $locale);
        }

        // Set application locale
        App::setLocale($locale);

        return $next($request);
    }
}
