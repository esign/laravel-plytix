<?php

namespace Esign\Plytix\DataTransferObjects\V1;

use Esign\Plytix\DataTransferObjects\Casts\PlytixDateTimeCast;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class ProductCategory extends Data
{
    public function __construct(
        public readonly string $id,
        #[WithCast(PlytixDateTimeCast::class)]
        public readonly ?Carbon $modified,
        public readonly ?int $nChildren,
        public readonly ?string $name,
        public readonly ?string $order,
        public readonly ?array $parentsIds,
        public readonly ?array $path,
        public readonly ?string $slug,
    ) {
    }
}
