<?php

namespace Esign\Plytix\Requests\V2;

use Esign\Plytix\DataTransferObjects\V2\Product;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Body\HasJsonBody;

class ProductSearchRequest extends Request implements HasBody, Paginatable
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/api/v2/products/search';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $product) {
            return Product::from($product);
        }, $response->json('data'));
    }
}
