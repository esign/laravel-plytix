<?php

namespace Esign\Plytix\DataTransferObjects;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class Product extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $sku,
        public readonly ?string $label,
        public readonly ?string $status,
        public readonly ?int $numVariations,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.uP')]
        public readonly ?Carbon $modified,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.uP')]
        public readonly ?Carbon $created,
        public readonly array $attributes,
        #[DataCollectionOf(ProductCategory::class)]
        public readonly ?array $categories,
    ) {
    }
}
