<?php

namespace Esign\Plytix\Requests\V1;

use Esign\Plytix\DataTransferObjects\V1\Relationship;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateRelationshipRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected array $payload)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/api/v1/relationships';
    }

    public function defaultBody(): array
    {
        return $this->payload;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $relationship) {
            return Relationship::from($relationship);
        }, $response->json('data'));
    }
}
