<?php

namespace Esign\Plytix\Requests\V1;

use Esign\Plytix\DataTransferObjects\V1\ProductAttributeGroup;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Body\HasJsonBody;

class ProductAttributeGroupSearchRequest extends Request implements HasBody, Paginatable
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/api/v1/attribute-groups/product/search';
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $productAttributeGroup) {
            return ProductAttributeGroup::from($productAttributeGroup);
        }, $response->json('data'));
    }
}
