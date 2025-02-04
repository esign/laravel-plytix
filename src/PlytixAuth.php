<?php

namespace Esign\Plytix;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class PlytixAuth extends Connector
{
    use AlwaysThrowOnErrors;
    use AcceptsJson;

    public function resolveBaseUrl(): string
    {
        return 'https://auth.plytix.com';
    }
}
