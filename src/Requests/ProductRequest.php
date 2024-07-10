<?php

namespace Esign\Plytix\Requests;

use Esign\Plytix\DataTransferObjects\Product;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ProductRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $productId
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/api/v1/products/{$this->productId}";
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return Product::from($response->json('data.0'));
    }
}
