<?php

namespace Esign\Plytix\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class TokenRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $apiKey,
        protected string $apiPassword,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/auth/api/get-token';
    }

    protected function defaultBody(): array
    {
        return [
            'api_key' => $this->apiKey,
            'api_password' => $this->apiPassword,
        ];
    }
}
