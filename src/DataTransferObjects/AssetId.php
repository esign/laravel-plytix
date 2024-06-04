<?php

namespace Esign\Plytix\DataTransferObjects;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class AssetId extends Data
{
    public function __construct(
        public readonly string $id
    ) {}
}