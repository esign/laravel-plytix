<?php

namespace Esign\Plytix\DataTransferObjects\Casts;

use Spatie\LaravelData\Casts\DateTimeInterfaceCast;

class PlytixDateTimeCast extends DateTimeInterfaceCast
{
    public const FORMATS = [
        'Y-m-d\TH:i:s.uP',
        'Y-m-d\TH:i:sP',
        'Y-m-d\TH:i:s.u',
    ];

    public function __construct(
        ?string $type = null,
        ?string $setTimeZone = null,
        ?string $timeZone = null
    ) {
        parent::__construct(
            format: static::FORMATS,
            type: $type,
            setTimeZone: $setTimeZone,
            timeZone: $timeZone
        );
    }
}
