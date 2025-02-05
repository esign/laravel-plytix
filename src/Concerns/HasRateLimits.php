<?php

namespace Esign\Plytix\Concerns;

use Esign\Plytix\Enums\RateLimitingPlan;
use Esign\Plytix\Exceptions\InvalidConfigurationException;
use Illuminate\Support\Facades\Cache;
use Saloon\RateLimitPlugin\Contracts\RateLimitStore;
use Saloon\RateLimitPlugin\Stores\LaravelCacheStore;
use Saloon\RateLimitPlugin\Traits\HasRateLimits as BaseHasRateLimits;

trait HasRateLimits
{
    use BaseHasRateLimits;

    protected function resolveRateLimitStore(): RateLimitStore
    {
        return new LaravelCacheStore(
            Cache::store(config('plytix.rate_limiting.cache_store'))
        );
    }

    protected function resolveLimits(): array
    {
        $rateLimitingPlan = config('plytix.rate_limiting.plan');

        if (! $rateLimitingPlan instanceof RateLimitingPlan) {
            throw InvalidConfigurationException::invalidRateLimitingPlan();
        }

        return $rateLimitingPlan->limits();
    }
}
