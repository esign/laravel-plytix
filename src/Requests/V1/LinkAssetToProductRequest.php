<?php

namespace Esign\Plytix\Requests\V1;

use Esign\Plytix\DataTransferObjects\V1\Asset;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class LinkAssetToProductRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $productId,
        protected array $payload
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/api/v1/products/' . $this->productId . "/assets";
    }

    public function defaultBody(): array
    {
        return $this->payload;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $asset) {
            return Asset::from($asset);
        }, $response->json('data'));
    }
}
