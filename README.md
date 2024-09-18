# A Laravel SDK for the Plytix API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/esign/laravel-plytix.svg?style=flat-square)](https://packagist.org/packages/esign/laravel-plytix)
[![Total Downloads](https://img.shields.io/packagist/dt/esign/laravel-plytix.svg?style=flat-square)](https://packagist.org/packages/esign/laravel-plytix)
![GitHub Actions](https://github.com/esign/laravel-plytix/actions/workflows/main.yml/badge.svg)

This package allow you to easily interact with the [Plytix API](https://apidocs.plytix.com).

## Installation

You can install the package via composer:

```bash
composer require esign/laravel-plytix
```

The package will automatically register a service provider.

Next up, you can publish the configuration file:
```bash
php artisan vendor:publish --provider="Esign\Plytix\PlytixServiceProvider" --tag="config"
```

The config file will be published as config/plytix.php with the following content:
```php
<?php

use Esign\Plytix\Enums\RateLimitingPlan;

return [
    /**
     * The API key to be used for authenticating with the Plytix API.
     */
    'api_key' => env('PLYTIX_API_KEY'),

    /**
     * The API password to be used for authenticating with the Plytix API.
     */
    'api_password' => env('PLYTIX_API_PASSWORD'),

    'authenticator_cache' => [
        /**
         * The key that will be used to cache the Plytix access token.
         */
        'key' => 'esign.plytix.authenticator',

        /**
         * The cache store to be used for the Plytix access token.
         * Use null to utilize the default cache store from the cache.php config file.
         * To disable caching, you can use the 'array' store.
         */
        'store' => null,
    ],

    'rate_limiting' => [
        /**
         * The rate limits to be used for the Plytix API.
         */
        'plan' => RateLimitingPlan::FREE,

        /**
         * The cache store to be used for the Plytix rate limits.
         * Use null to utilize the default cache store from the cache.php config file.
         * To disable caching, you can use the 'array' store.
         */
        'cache_store' => null,
    ],
];

```

## Usage
This package leverages [Saloon](https://docs.saloon.dev/) for sending requests to the Plytix API, providing an elegant way to interact with the API.

### Authentication
After configuring your API key and password in the config file, authentication tokens are automatically managed by the package. There's no need to manually handle authentication tokens.

### Rate limiting
The Plytix API enforces rate limits based on the subscription plan you’re on. You can configure these rate limits in the `config/plytix.php` file under the rate_limiting section.

### Sending requests
To send requests to the Plytix API, you may use the `Esign\Plytix\Plytix` connector.    
You can explore all the available request classes in the [src/Requests](src/Requests) directory.    
Here’s an example of sending a request:

```php
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\ProductRequest;

class MyCommand
{
    public function handle(): int
    {
        $plytix = Plytix::make();
        $response = $plytix->send(new ProductRequest('583bef16-2197-46dd-90ce-9f4210bef5ef'));

        return self::SUCCESS;
    }
}
```

### Response handling
This package uses DTOs (Data Transfer Objects) to simplify working with API responses. You can retrieve the DTO from the response like this:
```php
$plytix = Plytix::make();
$response = $plytix->send(new ProductRequest('583bef16-2197-46dd-90ce-9f4210bef5ef'));
$product = $response->dtoOrFail();
```

Alternatively, you can retrieve the raw response data:
```php
$plytix = Plytix::make();
$response = $plytix->send(new ProductRequest('583bef16-2197-46dd-90ce-9f4210bef5ef'));
$data = $response->body();
```

### Paginating requests
For endpoints that return paginated data, you can easily paginate through the responses:
```php
$plytix = Plytix::make();
$paginator = $plytix->paginate(new ProductSearchRequest());

foreach ($paginator as $response) {
    $products = $response->dtoOrFail();
}
```

Additionally, you may also traverse through these pages using the `items` or `collect` methods.    
For more information, refer to the [Saloon pagination documentation.](https://docs.saloon.dev/installable-plugins/pagination#using-the-paginator)
```php
$plytix = Plytix::make();
$paginator = $plytix->paginate(new ProductSearchRequest());

foreach ($paginator->items() as $product) {
    // Do something with the product
}

$paginator->collect()->each(function ($product) {
    // Do something with the product
});
```

### Creating requests
#### Extending existing requests
If you need to customize or extend an existing request, you can create your own request class by extending the base request class:
```php
use Esign\Plytix\Requests\ProductRequest;

class MyCustomProductRequest extends ProductRequest
{
    protected function defaultQuery(): array
    {
        return [
            'customer-query-param' => 'value',
        ];
    }

}
```

#### Creating custom requests
If the request you need is not provided by the package, you can create a custom request using Saloon.
First, install the [Saloon Laravel plugin](https://docs.saloon.dev/installable-plugins/laravel-integration), then use the following command:
```bash
php artisan saloon:request Plytix MyCustomRequest
```
```php
use Saloon\Enums\Method;
use Saloon\Http\Request;

class MyCustomRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/example';
    }
}
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
