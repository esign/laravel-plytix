<?php

namespace Esign\Plytix\DataTransferObjects\V1;

use Esign\Plytix\DataTransferObjects\Casts\PlytixDateTimeCast;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class Relationship extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $label,
        public readonly ?string $name,
        #[WithCast(PlytixDateTimeCast::class)]
        public readonly ?Carbon $created,
        #[WithCast(PlytixDateTimeCast::class)]
        public readonly ?Carbon $modified,
    ) {
    }
}
