<?php

namespace Esign\Plytix\Requests\V1;

use Esign\Plytix\DataTransferObjects\V1\ProductCategory;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateProductSubcategoryRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $categoryId,
        protected array $payload
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/api/v1/categories/product/' . $this->categoryId;
    }

    public function defaultBody(): array
    {
        return $this->payload;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $productCategory) {
            return ProductCategory::from($productCategory);
        }, $response->json('data'));
    }
}
