<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user->current_tenant_id) {
            return response()->json(['message' => 'No Active Tenant Selected!'], 400);
        }

        // Double check membership (safety net)
        if (! $user->tenants()->where('tenant_id', $user->current_tenant_id)->exists()) {
            return response()->json(['message' => 'Invalid tenant context'], 403);
        }

        // optionally bind it for services
        app()->instance('tenant_id', $user->current_tenant_id);

        return $next($request);
    }
}
