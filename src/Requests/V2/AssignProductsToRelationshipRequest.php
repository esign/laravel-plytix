<?php

namespace Esign\Plytix\Requests\V2;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class AssignProductsToRelationshipRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $productId,
        protected string $relationshipId,
        protected array $payload
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/api/v2/products/' . $this->productId . "/relationships/" . $this->relationshipId;
    }

    public function defaultBody(): array
    {
        return $this->payload;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return null;
    }
}
