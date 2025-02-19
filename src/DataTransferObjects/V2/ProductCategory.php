<?php

namespace Esign\Plytix\DataTransferObjects\V2;

use Spatie\LaravelData\Data;

class ProductCategory extends Data
{
    public function __construct(
        public readonly string $id,
    ) {}
}
