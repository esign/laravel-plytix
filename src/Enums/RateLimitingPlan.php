<?php

namespace Esign\Plytix\Enums;

use Saloon\RateLimitPlugin\Limit;

enum RateLimitingPlan
{
    case FREE;
    case PAID;

    public function limits(): array
    {
        return match ($this) {
            self::FREE => [
                Limit::allow(20)->everySeconds(10),
                Limit::allow(2000)->everyHour(),
            ],
            self::PAID => [
                Limit::allow(20)->everySeconds(10),
                Limit::allow(5000)->everyHour(),
            ],
        };
    }
}
