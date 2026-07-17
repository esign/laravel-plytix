<?php

namespace Esign\Plytix\Requests\V1;

use Esign\Plytix\DataTransferObjects\V1\ProductAttribute;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateProductAttributeRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        protected string $productAttributeId,
        protected array $payload
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/api/v1/attributes/product/' . $this->productAttributeId;
    }

    public function defaultBody(): array
    {
        return $this->payload;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $attribute) {
            return ProductAttribute::from($attribute);
        }, $response->json('data'));
    }
}
