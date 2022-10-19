<?php

namespace MichaelNabil230\BlockIp;

use Illuminate\Cache\CacheManager;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use MichaelNabil230\BlockIp\Models\BlockIp;
use MichaelNabil230\BlockIp\Services\Authentication;

class BlockIpRegistrar
{
    /** @var \Illuminate\Contracts\Cache\Repository */
    protected $cache;

    /** @var \Illuminate\Cache\CacheManager */
    protected $cacheManager;

    /** @var string */
    public static $cacheKey;

    /** @var \DateInterval|int */
    protected $cacheExpirationTime;

    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
        $this->initializeCache();
    }

    protected function initializeCache()
    {
        $this->cacheExpirationTime = config('block-ip.cache.expiration_time', \DateInterval::createFromDateString('24 hours'));
        self::$cacheKey = config('block-ip.cache.key', 'block-ips.cache.');

        $this->cache = $this->getCacheStoreFromConfig();
    }

    protected function getCacheStoreFromConfig(): Repository
    {
        // the 'default' fallback here is from the block-ip.php config file,
        // where 'default' means to use config(cache.default)
        $cacheDriver = config('block-ip.cache.store', 'default');

        // when 'default' is specified, no action is required since we already have the default instance
        if ($cacheDriver === 'default') {
            return $this->cacheManager->store();
        }

        // if an undefined cache store is specified, fallback to 'array' which is Laravel's closest equiv to 'none'
        if (! array_key_exists($cacheDriver, config('cache.stores'))) {
            $cacheDriver = 'array';
        }

        return $this->cacheManager->store($cacheDriver);
    }

    public function getBlockIpCached($ip): bool
    {
        return $this->cache->get(self::$cacheKey.$ip, false);
    }

    public function forgetCachedBlockIp($ip): bool
    {
        return $this->cache->forget(self::$cacheKey.$ip);
    }

    public function addCachedBlockIp(string $ip): mixed
    {
        return $this->cache->add(self::$cacheKey.$ip, true, $this->cacheExpirationTime);
    }

    public static function rateLimiter(int $maxAttempts = 60): void
    {
        RateLimiter::for('block-ip', function (Request $request) use ($maxAttempts) {
            if (in_array($request->ip(), config('block-ip.truest_ips', []))) {
                return Limit::none();
            }

            return Limit::perMinute($maxAttempts)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function () use ($request) {
                    $ip = $request->ip();

                    if (! app(BlockIpRegistrar::class)->getBlockIpCached($ip)) {
                        $data = ['ip_address' => $ip];

                        if ($user = Authentication::getUser()) {
                            $data += [
                                'authenticatable_type' => Model::getActualClassNameForMorph(get_class($user)),
                                'authenticatable_id' => $user->getAuthIdentifier(),
                            ];
                        }

                        BlockIp::create($data);
                    }

                    return abort(403, 'You are restricted to access the site.');
                });
        });
    }
}
