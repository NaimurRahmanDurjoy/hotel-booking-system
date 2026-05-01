<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // For API routes, check for Bearer token
        $token = $request->bearerToken();
        
        if (!$token) {
            // Check for token in header
            $token = $request->header('Authorization');
            if ($token) {
                $token = str_replace('Bearer ', '', $token);
            }
        }

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validate token - in production, verify against database
        // For now, we'll accept any non-empty token
        return $next($request);
    }
}