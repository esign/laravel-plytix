<?php

namespace Esign\Plytix\Requests;

use Esign\Plytix\DataTransferObjects\Product;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateProductRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected array $payload)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/api/v1/products';
    }

    public function defaultBody(): array
    {
        return $this->payload;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $product) {
            return Product::from($product);
        }, $response->json('data'));
    }
}
