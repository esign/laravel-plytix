<?php

namespace Esign\Plytix\DataTransferObjects\V2;

use Esign\Plytix\DataTransferObjects\Casts\PlytixDateTimeCast;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class Product extends Data
{
    public function __construct(
        #[MapInputName(('_id'))]
        public readonly string $id,
        public readonly string $sku,
        public readonly ?string $label,
        public readonly ?string $status,
        public readonly ?int $numVariations,
        #[WithCast(PlytixDateTimeCast::class)]
        public readonly ?Carbon $modified,
        #[WithCast(PlytixDateTimeCast::class)]
        public readonly ?Carbon $created,
        public readonly array $attributes,
        #[DataCollectionOf(RelationshipInformation::class)]
        public readonly ?array $relationships,
        public readonly ?array $categoryIds,
        public readonly ?array $assetIds,
    ) {
    }
}
