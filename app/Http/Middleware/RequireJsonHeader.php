<?php

namespace App\Http\Middleware;

use App\Api\ApiResponse\Facades\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireJsonHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->expectsJson()) {
            return ApiResponse::withStatus(406)
                ->withMessage('You must set the Accept header to application/json.')
                ->send();
        }
        return $next($request);
    }
}
