<?php

namespace Esign\Plytix\Requests;

use Esign\Plytix\DataTransferObjects\ProductVariant;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Body\HasJsonBody;

class ProductVariantRequest extends Request implements HasBody, Paginatable
{
    use HasJsonBody;

    protected Method $method = Method::GET;

    public function __construct(
        protected string $productId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/api/v1/products/' . $this->productId . '/variants';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $variation) {
            return ProductVariant::from($variation);
        }, $response->json('data'));
    }
}
