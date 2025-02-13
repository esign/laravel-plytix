<?php

namespace Esign\Plytix;

use Esign\Plytix\Pagination\PagedPaginator;
use Esign\Plytix\Requests\TokenRequest;
use Illuminate\Support\Facades\Cache;
use Saloon\Contracts\Authenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\RateLimitPlugin\Contracts\RateLimitStore;
use Saloon\RateLimitPlugin\Stores\LaravelCacheStore;
use Saloon\RateLimitPlugin\Traits\HasRateLimits;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class Plytix extends Connector implements HasPagination
{
    use AlwaysThrowOnErrors;
    use HasRateLimits;

    public function resolveBaseUrl(): string
    {
        return 'https://pim.plytix.com';
    }

    protected function defaultAuth(): ?Authenticator
    {
        $cacheStore = Cache::store(config('plytix.authenticator_cache.store'));
        $cachedAuthenticator = $cacheStore->get(config('plytix.authenticator_cache.key'));

        if ($cachedAuthenticator instanceof PlytixTokenAuthenticator && ! $cachedAuthenticator->hasExpired()) {
            return $cachedAuthenticator;
        }

        $tokenResponse = (new PlytixAuth())->send(new TokenRequest(
            config('plytix.api_key'),
            config('plytix.api_password')
        ));

        $authenticator = new PlytixTokenAuthenticator($tokenResponse->json('data.0.access_token'));

        $cacheStore->put(
            key: config('plytix.authenticator_cache.key'),
            value: $authenticator,
            ttl: $authenticator->expiresAt
        );

        return $authenticator;
    }

    protected function resolveRateLimitStore(): RateLimitStore
    {
        return new LaravelCacheStore(
            Cache::store(config('plytix.rate_limiting.cache_store'))
        );
    }

    protected function resolveLimits(): array
    {
        return app(RateLimiter::class)->getLimits();
    }

    public function paginate(Request $request): PagedPaginator
    {
        return new PagedPaginator(
            connector: $this,
            request: $request,
        );
    }
}
