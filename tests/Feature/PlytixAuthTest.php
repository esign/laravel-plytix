<?php

namespace Esign\Plytix\Tests\Feature;

use Esign\Plytix\Enums\RateLimitingPlan;
use Esign\Plytix\PlytixAuth;
use Esign\Plytix\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Saloon\RateLimitPlugin\Limit;

class PlytixAuthTest extends TestCase
{
    /** @test */
    public function it_can_use_the_rate_limiting_plan_defined_in_the_config(): void
    {
        Config::set('plytix.rate_limiting.plan', RateLimitingPlan::PAID);
        $connector = new PlytixAuth();

        $limits = $connector->getLimits();

        $this->assertLimitsContain(limits: $limits, allow: 20, releaseInSeconds: 10);
        $this->assertLimitsContain(limits: $limits, allow: 5000, releaseInSeconds: 3600);
    }

    protected function assertLimitsContain(array $limits, int $allow, int $releaseInSeconds): void
    {
        $this->assertTrue(collect($limits)->contains(function (Limit $limit) use ($allow, $releaseInSeconds) {
            return $limit->getAllow() === $allow && $limit->getReleaseInSeconds() === $releaseInSeconds;
        }));
    }
}
