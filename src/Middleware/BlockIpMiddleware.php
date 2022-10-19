<?php

namespace MichaelNabil230\BlockIp\Middleware;

use Closure;
use Illuminate\Http\Request;
use MichaelNabil230\BlockIp\Models\BlockIp;

class BlockIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        abort_if(BlockIp::where('ip_address', $request->ip())->exists(), 403, 'You are restricted to access the site.');

        return $next($request);
    }
}
