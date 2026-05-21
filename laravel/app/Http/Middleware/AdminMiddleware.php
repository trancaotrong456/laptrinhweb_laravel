<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || (int) $request->user()->role !== 1) {
            abort(403, 'Ban khong co quyen truy cap trang nay.');
        }

        return $next($request);
    }
}
