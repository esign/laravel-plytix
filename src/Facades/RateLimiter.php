<?php

namespace Esign\Plytix\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Esign\Plytix\RateLimiter setLimits(array|\Saloon\RateLimitPlugin\Limit ...$limits)
 * @method static array getLimits()
 */
class RateLimiter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Esign\Plytix\RateLimiter::class;
    }
}
