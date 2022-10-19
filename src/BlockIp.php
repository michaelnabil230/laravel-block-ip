<?php

namespace MichaelNabil230\BlockIp;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use MichaelNabil230\BlockIp\Models\BlockIp as ModelBlockIp;
use MichaelNabil230\BlockIp\Services\Authentication;

class BlockIp
{
    public static function rateLimiter(string $name = 'log', int $maxAttempts = 10): void
    {
        RateLimiter::for($name, function (Request $request) use ($maxAttempts) {
            return Limit::perMinute($maxAttempts)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function () use ($request) {
                    if (in_array($request->ip(), config('block-ip.truest_ips', []))) {
                        return;
                    }

                    $data = [
                        'ip_address' => $request->ip(),
                    ];

                    if ($user = Authentication::getUser()) {
                        $data += [
                            'authenticatable_type' => get_class($user),
                            'authenticatable_id' => $user->getAuthIdentifier(),
                        ];
                    }

                    ModelBlockIp::create($data);

                    abort(403, 'You are restricted to access the site.');
                });
        });
    }
}
