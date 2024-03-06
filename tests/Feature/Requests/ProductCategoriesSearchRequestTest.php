<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\ProductCategoriesSearchRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class ProductCategoriesSearchRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_product_categories_search_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product-categories-search.json', status: 200),
        ]);

        $response = $plytix->send(new ProductCategoriesSearchRequest());

        $mockClient->assertSent(ProductCategoriesSearchRequest::class);
        $this->assertEquals('5cf4d55eb694740001006ed1', $response->json('data.0.id'));
        $this->assertEquals('Kitchen Sinks', $response->json('data.0.name'));
    }
}
