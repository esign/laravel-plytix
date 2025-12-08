<?php

namespace Esign\Plytix;

use DateTimeImmutable;
use Saloon\Contracts\Authenticator;
use Saloon\Http\PendingRequest;

class PlytixTokenAuthenticator implements Authenticator
{
    private const LEEWAY_SECONDS = 5;

    public function __construct(
        public readonly string $token,
        public readonly DateTimeImmutable $expiresAt = new DateTimeImmutable('+15 minutes'),
    ) {
    }

    public function set(PendingRequest $pendingRequest): void
    {
        $pendingRequest->headers()->add('Authorization', 'Bearer ' . $this->token);
    }

    public function hasExpired(): bool
    {
        $currentTimeWithLeeway = now()->addSeconds(self::LEEWAY_SECONDS);

        return $currentTimeWithLeeway->getTimestamp() >= $this->expiresAt->getTimestamp();
    }
}
