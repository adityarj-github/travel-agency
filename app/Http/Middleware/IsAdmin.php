<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Allow only authenticated admin users through.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || ! Auth::user()->isAdmin()) {
            Auth::logout();

            return redirect()
                ->route('admin.login')
                ->with('error', 'You must be logged in as an administrator to access that area.');
        }

        return $next($request);
    }
}
