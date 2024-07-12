<?php

namespace Esign\Plytix\DataTransferObjects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class ProductAttributeGroup extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $name,
        public readonly ?int $order,
        public readonly ?array $attributeLabels,
    ) {
    }
}
