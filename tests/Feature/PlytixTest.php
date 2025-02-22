<?php

namespace Esign\Plytix\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use DateTimeImmutable;
use Esign\Plytix\Facades\RateLimiter;
use Esign\Plytix\Plytix;
use Esign\Plytix\PlytixTokenAuthenticator;
use Esign\Plytix\Requests\V2\CreateProductRequest;
use Esign\Plytix\Requests\TokenRequest;
use Esign\Plytix\Requests\V2\UpdateProductRequest;
use Esign\Plytix\Tests\Support\AssertsRateLimits;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\RateLimitPlugin\Limit;

final class PlytixTest extends TestCase
{
    use AssertsRateLimits;

    #[Test]
    public function it_can_use_a_cached_token_when_it_is_valid(): void
    {
        $this->storeAccessTokenInCache(new DateTimeImmutable('+1 hour'));
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'V2/create-product.json', status: 201),
        ]);

        $plytix->send(new CreateProductRequest(['sku' => '12345']));

        $mockClient->assertNotSent(TokenRequest::class);
        $mockClient->assertSent(CreateProductRequest::class);
    }

    #[Test]
    public function it_can_request_a_new_token_when_it_has_expired(): void
    {
        $this->storeAccessTokenInCache(new DateTimeImmutable('-1 minute'));
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V2/create-product.json', status: 201),
        ]);

        $plytix->send(new CreateProductRequest(['sku' => '12345']));

        $mockClient->assertSentCount(1, TokenRequest::class);
        $mockClient->assertSentCount(1, CreateProductRequest::class);
    }

    #[Test]
    public function it_can_use_a_cached_token_when_performing_multiple_requests(): void
    {
        $this->storeAccessTokenInCache(new DateTimeImmutable('+1 hour'));
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'V2/create-product.json', status: 201),
            MockResponseFixture::make(fixtureName: 'V2/create-product.json', status: 201),
        ]);

        $plytix->send(new CreateProductRequest(['sku' => '12345']));
        $plytix->send(new CreateProductRequest(['sku' => '12345']));

        $mockClient->assertNotSent(TokenRequest::class);
        $mockClient->assertSentCount(2, CreateProductRequest::class);
    }

    #[Test]
    public function it_can_throw_an_exception_when_an_http_error_is_encoutered_while_requesting_an_access_token(): void
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponse::make(status: 503),
        ]);

        $this->expectException(RequestException::class);
        $plytix->send(new CreateProductRequest(['sku' => '12345']));

        $mockClient->assertNotSent(CreateProductRequest::class);
    }

    #[Test]
    public function it_can_throw_an_exception_when_an_http_error_is_encoutered(): void
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V2/update-product-not-found.json', status: 404),
        ]);

        $this->expectException(RequestException::class);

        $plytix->send(new UpdateProductRequest(
            productId: '5c4ed8002f0985001e233279',
            payload: []
        ));
    }

    #[Test]
    public function it_can_use_the_rate_limiting_plan_defined_in_the_config(): void
    {
        RateLimiter::setLimits(
            Limit::allow(20)->everySeconds(10),
            Limit::allow(5000)->everyHour(),
        );
        $connector = new Plytix();

        $limits = $connector->getLimits();

        $this->assertLimitsContain(limits: $limits, allow: 20, releaseInSeconds: 10);
        $this->assertLimitsContain(limits: $limits, allow: 5000, releaseInSeconds: 3600);
    }

    protected function storeAccessTokenInCache(DateTimeImmutable $expiresAt): void
    {
        Cache::store(config('plytix.authenticator_cache.store'))->put(
            config('plytix.authenticator_cache.key'),
            new PlytixTokenAuthenticator('fake-token', $expiresAt),
        );
    }
}
