<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckDatabase
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database not configured or unreachable.',
                    'details' => config('app.debug') ? $e->getMessage() : null,
                ], 503);
            }

            return response()->make('<h1>Service Unavailable</h1><p>Database not configured or unreachable.</p>', 503);
        }

        return $next($request);
    }
}
