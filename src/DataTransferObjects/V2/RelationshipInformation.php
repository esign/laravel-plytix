<?php

namespace Esign\Plytix\DataTransferObjects\V2;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class RelationshipInformation extends Data
{
    public function __construct(
        public readonly string $relationshipId,
        public readonly string $relationshipLabel,
        #[DataCollectionOf(RelatedProduct::class)]
        public readonly array $linksTo,
    ) {
    }
}
