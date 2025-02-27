<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Example logic: must be authenticated & an admin
        // for mysql code

        // if (!Auth::check() || Auth::user()->is_admin !== true) {
        //    abort(403, 'Unauthorized action.');
        // }
        // sqlite
        // If not authenticated, redirect to admin login
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        // If authenticated but not an admin, abort with 403
        if (!Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
