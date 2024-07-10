<?php

namespace Esign\Plytix\DataTransferObjects;

use Esign\Plytix\DataTransferObjects\Casts\PlytixDateTimeCast;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class ProductVariant extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $sku,
        public readonly ?string $label,
        public readonly ?string $status,
        #[WithCast(PlytixDateTimeCast::class)]
        public readonly ?Carbon $modified,
        #[WithCast(PlytixDateTimeCast::class)]
        public readonly ?Carbon $created,
        public readonly array $attributes,
        #[DataCollectionOf(RelationshipInformation::class)]
        public readonly ?array $relationships,
        #[DataCollectionOf(ProductCategory::class)]
        public readonly ?array $categories,
        #[DataCollectionOf(Asset::class)]
        public readonly ?array $assets,
    ) {
    }
}
