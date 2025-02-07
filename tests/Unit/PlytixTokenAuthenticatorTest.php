<?php

namespace Esign\Plytix\Tests\Unit;

use Esign\Plytix\PlytixTokenAuthenticator;
use Esign\Plytix\Tests\TestCase;

class PlytixTokenAuthenticatorTest extends TestCase
{
    /** @test */
    public function it_can_respect_a_leeway_when_checking_if_an_access_token_has_expired(): void
    {
        $plytixTokenAuthenticator = new PlytixTokenAuthenticator(
            token: 'access-token',
            expiresAt: now()->addMinutes(15)->toImmutable()
        );

        $this->travelTo(now()->addMinutes(15)->addSeconds(1));
        $this->assertFalse($plytixTokenAuthenticator->hasExpired());

        $this->travelTo(now()->addMinutes(15)->addSeconds(5));
        $this->assertTrue($plytixTokenAuthenticator->hasExpired());
    }
}
