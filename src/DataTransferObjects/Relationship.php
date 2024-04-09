<?php

namespace Esign\Plytix\DataTransferObjects;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class Relationship extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $label,
        public readonly ?string $name,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.uP')]
        public readonly ?Carbon $created,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.uP')]
        public readonly ?Carbon $modified,
    ) {
    }
}
