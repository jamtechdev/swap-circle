<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Ensures portal Stripe payment endpoints use the logged-in session buyer.
 * API group normally has no session — this middleware starts it for same-origin AJAX.
 */
class EnsurePortalCustomer
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('id') || !is_numeric(session('id')) || (int) session('id') <= 0) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required.',
                ], 401);
            }

            return redirect('/login');
        }

        // If client sends users_customers_id, it must match the session.
        if ($request->filled('users_customers_id') && is_numeric($request->users_customers_id)) {
            if ((int) $request->users_customers_id !== (int) session('id')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized purchase access',
                ], 403);
            }
        }

        $request->attributes->set('portal_users_customers_id', (int) session('id'));

        return $next($request);
    }
}
