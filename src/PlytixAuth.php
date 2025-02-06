<?php

namespace Esign\Plytix;

use Illuminate\Support\Facades\Cache;
use Saloon\Http\Connector;
use Saloon\RateLimitPlugin\Contracts\RateLimitStore;
use Saloon\RateLimitPlugin\Limit;
use Saloon\RateLimitPlugin\Stores\LaravelCacheStore;
use Saloon\RateLimitPlugin\Traits\HasRateLimits;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class PlytixAuth extends Connector
{
    use AlwaysThrowOnErrors;
    use AcceptsJson;
    use HasRateLimits;

    public function resolveBaseUrl(): string
    {
        return 'https://auth.plytix.com';
    }

    protected function resolveRateLimitStore(): RateLimitStore
    {
        return new LaravelCacheStore(
            Cache::store(config('plytix.rate_limiting.cache_store'))
        );
    }

    protected function resolveLimits(): array
    {
        return [
            Limit::allow(8)->everySeconds(1),
        ];
    }
}
