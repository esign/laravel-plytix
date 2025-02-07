<?php

namespace Esign\Plytix\Requests\V2;

use Esign\Plytix\DataTransferObjects\V2\Product;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateProductRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        protected string $productId,
        protected array $payload
    ) {}

    public function resolveEndpoint(): string
    {
        return '/api/v2/products/' . $this->productId;
    }

    public function defaultBody(): array
    {
        return $this->payload;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return Product::from($response->json('data.0'));
    }
}
