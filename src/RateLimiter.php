<?php

namespace Esign\Plytix;

use Saloon\RateLimitPlugin\Limit;

class RateLimiter
{
    protected array $limits = [];

    public function setLimits(Limit ...$limits): self
    {
        $this->limits = $limits;

        return $this;
    }

    public function getLimits(): array
    {
        return $this->limits;
    }
}
