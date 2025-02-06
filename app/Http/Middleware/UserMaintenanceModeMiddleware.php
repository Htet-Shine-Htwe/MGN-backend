<?php

namespace App\Http\Middleware;

use App\Models\ApplicationConfig;
use App\Services\ApplicationConfig\CacheApplicationConfigService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMaintenanceModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $is_maintenance_mode = ApplicationConfig::first()->user_side_is_maintenance_mode;
        $applicationConfig = (new CacheApplicationConfigService())->getApplicationConfig();

        if ($applicationConfig->user_side_is_maintenance_mode) {
            return response()->json([
                'message' => 'The application is in maintenance mode. Please try again later.'
            ], 503);
        }
        return $next($request);
    }
}
