<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiProvider extends Model
{
    protected $fillable = [
        'name',
        'key_prefix',
        'key_hash',
        'contact_email',
        'description',
        'permissions',
        'allowed_ips',
        'rate_limit_per_minute',
        'is_active',
        'last_used_at',
        'last_used_ip',
    ];

    protected $casts = [
        'permissions' => 'array',
        'allowed_ips' => 'array',
        'rate_limit_per_minute' => 'integer',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    public static function generatePlainKey(): string
    {
        $prefix = 'igrenc_' . Str::lower(Str::random(8));

        return $prefix . '.' . Str::random(64);
    }

    public static function prefixFromPlainKey(string $plainKey): string
    {
        return Str::before($plainKey, '.');
    }

    public static function hashPlainKey(string $plainKey): string
    {
        return hash('sha256', $plainKey);
    }

    public function isAllowedIp(?string $ip): bool
    {
        $allowedIps = $this->allowed_ips ?: [];

        if ($allowedIps === []) {
            return true;
        }

        return $ip && in_array($ip, $allowedIps, true);
    }

    public function markUsed(Request $request): void
    {
        $this->forceFill([
            'last_used_at' => now(),
            'last_used_ip' => $request->ip(),
        ])->saveQuietly();
    }
}
