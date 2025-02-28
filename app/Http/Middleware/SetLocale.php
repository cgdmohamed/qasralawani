<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;


class SetLocale
{
/**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('locale')) {
            $locale = Session::get('locale');
        } else {
            $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
            if (!in_array($locale, ['en', 'ar'])) {
                $locale = config('app.fallback_locale');
            }
            Session::put('locale', $locale);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
