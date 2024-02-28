<?php

namespace Esign\Plytix;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class PlytixAuth extends Connector
{
    use AcceptsJson;

    public function resolveBaseUrl(): string
    {
        return 'https://auth.plytix.com';
    }
}
