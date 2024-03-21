<?php

namespace Esign\Plytix\Requests;

use Esign\Plytix\DataTransferObjects\Asset;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use Saloon\Traits\Body\HasJsonBody;

class AssetSearchRequest extends Request implements HasBody, Paginatable
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/api/v1/assets/search';
    }

    protected function defaultBody(): array
    {
        return ['filters' => []];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return array_map(function (array $asset) {
            return Asset::from($asset);
        }, $response->json('data'));
    }
}
