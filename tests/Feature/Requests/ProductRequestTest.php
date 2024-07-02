<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\DataTransferObjects\Product;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\ProductRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class ProductRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_product_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product.json', status: 200),
        ]);

        $response = $plytix->send(new ProductRequest('5bfa8fba8544120001bd9073'));

        $mockClient->assertSent(ProductRequest::class);
        $this->assertEquals('5bfa8fba8544120001bd9073', $response->json('data.0.id'));
        $this->assertEquals('Product 1', $response->json('data.0.sku'));
    }

    /** @test */
    public function it_can_create_a_dto_from_a_response()
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product.json', status: 200),
        ]);

        $response = $plytix->send(new ProductRequest('5bfa8fba8544120001bd9073'));
        $product = $response->dto();

        $this->assertInstanceOf(Product::class, $product);
    }
}
