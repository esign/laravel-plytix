<?php

namespace Esign\Plytix\Requests\V1;

use Esign\Plytix\DataTransferObjects\V1\ProductAttribute;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Body\HasJsonBody;

class ProductAttributeSearchRequest extends Request implements HasBody, Paginatable
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/api/v1/attributes/product/search';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $productAttribute) {
            return ProductAttribute::from($productAttribute);
        }, $response->json('data'));
    }
}
