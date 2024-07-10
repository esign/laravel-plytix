<?php

namespace Esign\Plytix\DataTransferObjects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class ProductAttribute extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly ?array $groups,
        public readonly ?string $label,
        public readonly ?string $name,
        public readonly ?string $typeClass
    ) {
    }
}
