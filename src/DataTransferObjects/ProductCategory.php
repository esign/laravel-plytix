<?php

namespace Esign\Plytix\DataTransferObjects;

use DateTime;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class ProductCategory extends Data
{
    public function __construct(
        public readonly string $id,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.uP')]
        public readonly ?Carbon $modified,
        public readonly ?int $nChildren,
        public readonly ?string $name,
        public readonly ?string $order,
        public readonly ?array $parentsIds,
        public readonly ?array $path,
        public readonly ?string $slug,
    ) {}
}
