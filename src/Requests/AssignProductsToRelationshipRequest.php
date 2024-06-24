<?php

namespace Esign\Plytix\Requests;

use Esign\Plytix\DataTransferObjects\ProductCategory;
use Esign\Plytix\DataTransferObjects\RelationshipInformation;
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
    ) { }

    public function resolveEndpoint(): string
    {
        return '/api/v1/products/' . $this->productId . "/relationships/" . $this->relationshipId;
    }

    public function defaultBody(): array
    {
        return $this->payload;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $relationshipInfo) {
            return RelationshipInformation::from($relationshipInfo);
        }, $response->json('data'));
    }
}
