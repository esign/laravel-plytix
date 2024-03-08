<?php

namespace Esign\Plytix\Exceptions;

use Esign\Plytix\Enums\RateLimitingPlan;
use Exception;

class InvalidConfigurationException extends Exception
{
    public static function invalidRateLimitingPlan(): self
    {
        return new static(sprintf(
            'The provided rate limiting plan is invalid. Please configure a valid rate limiting plan from the %s enum.',
            RateLimitingPlan::class,
        ));
    }
}
