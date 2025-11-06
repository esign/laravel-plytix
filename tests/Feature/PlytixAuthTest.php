<?php

namespace Esign\Plytix\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\PlytixAuth;
use Esign\Plytix\Tests\Support\AssertsRateLimits;
use Esign\Plytix\Tests\TestCase;
use Saloon\RateLimitPlugin\Limit;

final class PlytixAuthTest extends TestCase
{
    use AssertsRateLimits;

    #[Test]
    public function it_can_respect_rate_limiting(): void
    {
        $connector = new PlytixAuth();

        $limits = $connector->getLimits();

        $this->assertLimitsContain(limits: $limits, allow: 8, releaseInSeconds: 1);
    }
}
