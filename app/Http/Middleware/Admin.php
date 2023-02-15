<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Admin
{
    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->isAdmin()) {
            return $next($request);
        }

        abort(Response::HTTP_FORBIDDEN, 'You are not an admin.');
    }
}
