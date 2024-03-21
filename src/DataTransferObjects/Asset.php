<?php

namespace Esign\Plytix\DataTransferObjects;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class Asset extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly ?bool $assigned,
        public readonly ?array $categories,
        public readonly ?string $contentType,
        public readonly ?string $filename,
        public readonly ?string $extension,
        public readonly ?int $fileSize,
        public readonly ?string $fileType,
        public readonly ?bool $hasCustomThumb,
        public readonly ?int $nCatalogs,
        public readonly ?int $nProducts,
        public readonly ?bool $public,
        public readonly ?string $status,
        public readonly ?string $thumbnail,
        public readonly ?string $url,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.uP')]
        public readonly ?Carbon $created,
        #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.uP')]
        public readonly ?Carbon $modified,
    ) {}
}