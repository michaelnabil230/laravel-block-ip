<?php

namespace MichaelNabil230\BlockIp\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Http;
use stdClass;

class CloudFlare
{
    public static function block(string $ip): object
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Auth-Key' => config('webhook_cloud_flare.block-ip.key'),
            'X-Auth-Email' => config('webhook_cloud_flare.block-ip.email'),
        ])
            ->post('https://api.cloudflare.com/client/v4/user/firewall/access_rules/rules', [
                'mode' => 'block',
                'configuration' => ['target' => 'ip', 'value' => $ip],
                'notes' => sprintf('Banned on %s by %s Firewall', [
                    date('Y-m-d H:i:s'),
                    config('app.name', 'Laravel Block IP'),
                ]),
            ]);

        $errors = $response->collect('errors');
        if ($errors->first()?->code === 9106) {
            throw new AuthorizationException();
        }

        if ($response->json('success', false)) {
            return $response->object();
        }

        return new stdClass();
    }

    public static function unblock(string $ip): bool
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Auth-Key' => config('webhook_cloud_flare.block-ip.key'),
            'X-Auth-Email' => config('webhook_cloud_flare.block-ip.email'),
        ])
            ->delete('https://api.cloudflare.com/client/v4/user/firewall/access_rules/rules'.$ip);

        $errors = $response->collect('errors');
        if ($errors->first()?->code === 9106) {
            throw new AuthorizationException();
        }

        return $response->json('success', false);
    }
}
