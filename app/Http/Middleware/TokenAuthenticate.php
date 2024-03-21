<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorizationToken = $request->bearerToken();

        if (!$authorizationToken) {
            return response()->json([
                'message' => 'Authorization token missing'
            ], Response::HTTP_BAD_REQUEST);
        }


        if ($authorizationToken !== config('blockchain.token')) {
            return response()->json(
                data : [
                    'status' => 'error',
                    'code' => Response::HTTP_FORBIDDEN,
                    'message' => 'Invalid token'
                ],
                status: 403,
                options: JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE
            );
        }

        return $next($request);
    }
}
