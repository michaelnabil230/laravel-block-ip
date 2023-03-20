<?php

namespace MichaelNabil230\BlockIp\Middleware;

use Closure;
use Illuminate\Http\Request;
use MichaelNabil230\BlockIp\BlockIpRegistrar;

class BlockIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();

        abort_if(app(BlockIpRegistrar::class)->getBlockIpCached($ip), 403, 'You are restricted to access the site.');

        return $next($request);
    }
}
