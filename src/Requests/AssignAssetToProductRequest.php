<?php

namespace Esign\Plytix\Requests;

use Esign\Plytix\DataTransferObjects\AssetId;
use Esign\Plytix\DataTransferObjects\ProductCategory;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class AssignAssetToProductRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $productId,
        protected array $payload
    ) { }

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
        return array_map(function (array $assetId) {
            return AssetId::from($assetId);
        }, $response->json('data'));
    }
}
