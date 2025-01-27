<?php

namespace Esign\Plytix\Requests\V1;

use Esign\Plytix\DataTransferObjects\V1\ProductCategory;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Body\HasJsonBody;

class ProductCategoriesSearchRequest extends Request implements HasBody, Paginatable
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/api/v1/categories/product/search';
    }

    public function createDtoFromResponse(Response $response): array
    {
        return array_map(function (array $productCategory) {
            return ProductCategory::from($productCategory);
        }, $response->json('data'));
    }
}
