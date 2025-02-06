<?php

namespace Esign\Plytix\Tests\Support;

use Saloon\RateLimitPlugin\Limit;

trait AssertsRateLimits
{
    /**
     * 
     * @param list<Limit> $limits 
     * @param int $allow 
     * @param int $releaseInSeconds 
     * @return void 
     */
    protected function assertLimitsContain(array $limits, int $allow, int $releaseInSeconds): void
    {
        $this->assertTrue(collect($limits)->contains(function (Limit $limit) use ($allow, $releaseInSeconds) {
            return $limit->getAllow() === $allow && $limit->getReleaseInSeconds() === $releaseInSeconds;
        }));
    }
}
