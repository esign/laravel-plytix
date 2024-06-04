<?php

namespace Esign\Plytix\Requests;


use Esign\Plytix\DataTransferObjects\Attribute;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateAttributeRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected array $payload)
    {
    }

    public function resolveEndpoint(): string
    {
        return '/api/v1/attributes/product';
    }

    public function defaultBody(): array
    {
        return $this->payload;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $attribute) {
            return Attribute::from($attribute);
        }, $response->json('data'));
    }
}
