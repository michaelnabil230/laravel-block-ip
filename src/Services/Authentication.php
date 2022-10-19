<?php

namespace MichaelNabil230\BlockIp\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class Authentication
{
    public static function getUser(): Authenticatable|null
    {
        $defaultGuards = array_keys(config('auth.guards'));

        $guards = empty($defaultGuards) ? [null] : $defaultGuards;

        foreach ($guards as $guard) {
            $auth = Auth::guard($guard);
            if ($auth->check()) {
                return $auth->user();
            }
        }

        return null;
    }
}
