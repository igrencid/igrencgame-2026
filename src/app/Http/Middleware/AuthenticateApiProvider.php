<?php

namespace App\Http\Middleware;

use App\Models\ApiProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiProvider
{
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        $plainKey = $request->header('X-Provider-Key');

        if (! $plainKey && Str::startsWith((string) $request->header('Authorization'), 'Bearer ')) {
            $plainKey = Str::after((string) $request->header('Authorization'), 'Bearer ');
        }

        $plainKey = trim((string) $plainKey);

        if ($plainKey === '') {
            return response()->json([
                'message' => 'Provider API key wajib dikirim.',
            ], 401);
        }

        $prefix = ApiProvider::prefixFromPlainKey($plainKey);

        $provider = ApiProvider::query()
            ->where('key_prefix', $prefix)
            ->where('is_active', true)
            ->first();

        if (! $provider || ! hash_equals($provider->key_hash, ApiProvider::hashPlainKey($plainKey))) {
            return response()->json([
                'message' => 'Provider API key tidak valid.',
            ], 401);
        }

        if (! $provider->isAllowedIp($request->ip())) {
            return response()->json([
                'message' => 'IP tidak diizinkan untuk provider ini.',
            ], 403);
        }

        if ($permission && ! in_array($permission, $provider->permissions ?: [], true)) {
            return response()->json([
                'message' => 'Provider tidak punya permission: ' . $permission,
            ], 403);
        }

        $rateKey = 'provider-api:' . $provider->id . ':' . sha1((string) $request->ip());
        $maxAttempts = max(1, (int) $provider->rate_limit_per_minute);

        if (RateLimiter::tooManyAttempts($rateKey, $maxAttempts)) {
            return response()->json([
                'message' => 'Terlalu banyak request. Coba lagi nanti.',
                'retry_after' => RateLimiter::availableIn($rateKey),
            ], 429);
        }

        RateLimiter::hit($rateKey, 60);

        $provider->markUsed($request);

        $request->attributes->set('api_provider', $provider);

        return $next($request);
    }
}
