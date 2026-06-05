<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomer
{
    /**
     * Guard the customer account area. Guests are sent to the customer login;
     * staff are bounced to the admin panel (their dashboard lives there).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->isStaff()) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
