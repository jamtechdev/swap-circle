<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * SC-07: Baseline security headers for all responses (login portal + app).
 * Prefer also setting these at nginx; app-level ensures they ship with the code.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        $scriptSrc = ["'self'", "'unsafe-inline'", 'https://cdn.jsdelivr.net'];
        $styleSrc = ["'self'", "'unsafe-inline'", 'https://fonts.googleapis.com', 'https://cdn.jsdelivr.net'];
        $connectSrc = ["'self'", 'https:', 'http:', 'ws:', 'wss:'];

        // Local Vite HMR (npm run dev) — required so auth CSS/JS is not blocked by CSP
        if (!app()->environment('production')) {
            $viteOrigins = [
                'http://127.0.0.1:5173',
                'http://localhost:5173',
                'ws://127.0.0.1:5173',
                'ws://localhost:5173',
            ];
            $scriptSrc = array_merge($scriptSrc, ['http://127.0.0.1:5173', 'http://localhost:5173']);
            $styleSrc = array_merge($styleSrc, ['http://127.0.0.1:5173', 'http://localhost:5173']);
            $connectSrc = array_merge($connectSrc, $viteOrigins);
        }

        $csp = implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "object-src 'none'",
            'script-src '.implode(' ', $scriptSrc),
            'style-src '.implode(' ', $styleSrc),
            "font-src 'self' data: https://fonts.gstatic.com",
            "img-src 'self' data: blob: https:",
            'connect-src '.implode(' ', $connectSrc),
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        // HSTS only over real HTTPS (supports reverse-proxy X-Forwarded-Proto)
        $forwardedProto = strtolower((string) $request->header('X-Forwarded-Proto', ''));
        $isHttps = $request->secure() || $forwardedProto === 'https';
        if ($isHttps) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
