<?php

namespace Esign\Plytix\Tests\Feature;

use DateTimeImmutable;
use Esign\Plytix\Plytix;
use Esign\Plytix\PlytixTokenAuthenticator;
use Esign\Plytix\Requests\V1\CreateProductRequest;
use Esign\Plytix\Requests\TokenRequest;
use Esign\Plytix\Requests\V1\UpdateProductRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Faking\MockClient;

class PlytixTest extends TestCase
{
    /** @test */
    public function it_can_use_a_cached_token_when_it_is_valid()
    {
        $this->storeAccessTokenInCache(new DateTimeImmutable('+1 hour'));
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'create-product.json', status: 201),
        ]);

        $plytix->send(new CreateProductRequest(['sku' => '12345']));

        $mockClient->assertNotSent(TokenRequest::class);
        $mockClient->assertSent(CreateProductRequest::class);
    }

    /** @test */
    public function it_can_request_a_new_token_when_it_has_expired()
    {
        $this->storeAccessTokenInCache(new DateTimeImmutable('-1 minute'));
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'create-product.json', status: 201),
        ]);

        $plytix->send(new CreateProductRequest(['sku' => '12345']));

        $mockClient->assertSentCount(1, TokenRequest::class);
        $mockClient->assertSentCount(1, CreateProductRequest::class);
    }

    /** @test */
    public function it_can_use_a_cached_token_when_performing_multiple_requests()
    {
        $this->storeAccessTokenInCache(new DateTimeImmutable('+1 hour'));
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'create-product.json', status: 201),
            MockResponseFixture::make(fixtureName: 'create-product.json', status: 201),
        ]);

        $plytix->send(new CreateProductRequest(['sku' => '12345']));
        $plytix->send(new CreateProductRequest(['sku' => '12345']));

        $mockClient->assertNotSent(TokenRequest::class);
        $mockClient->assertSentCount(2, CreateProductRequest::class);
    }

    /** @test */
    public function it_can_throw_an_exception_when_an_http_error_is_encoutered()
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'update-product-not-found.json', status: 404),
        ]);

        $this->expectException(RequestException::class);

        $plytix->send(new UpdateProductRequest(
            productId: '5c4ed8002f0985001e233279',
            payload: []
        ));
    }

    protected function storeAccessTokenInCache(DateTimeImmutable $expiresAt): void
    {
        Cache::store(config('plytix.authenticator_cache.store'))->put(
            config('plytix.authenticator_cache.key'),
            new PlytixTokenAuthenticator('fake-token', $expiresAt),
        );
    }
}
