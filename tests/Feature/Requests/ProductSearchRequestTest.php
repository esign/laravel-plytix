<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\DataTransferObjects\Product;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\ProductSearchRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class ProductSearchRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_product_categories_search_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product-search.json', status: 200),
        ]);

        $response = $plytix->send(new ProductSearchRequest());

        $mockClient->assertSent(ProductSearchRequest::class);
        $this->assertEquals('5ec383adf18d516fbbac718d', $response->json('data.0.id'));
        $this->assertEquals('1013', $response->json('data.0.sku'));
    }

    /** @test */
    public function it_can_create_a_dto_from_a_response_with_all_attributes()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product-search-with-all-attributes.json', status: 200),
        ]);

        $response = $plytix->send(new ProductSearchRequest());
        $products = $response->dto();

        $this->assertIsArray($products);
        $this->assertCount(8, $products);
        $this->assertInstanceOf(Product::class, $products[0]);
        $this->assertEquals('5ec383adf18d516fbbac718d', $products[0]->id);
        $this->assertEquals('1013', $products[0]->sku);
        $this->assertEquals('Backpack Venice - Coco', $products[0]->label);
        $this->assertEquals('Draft', $products[0]->status);
        $this->assertEquals(5, $products[0]->numVariations);
        $this->assertEquals('2020-05-19 07:03:05', $products[0]->modified->format('Y-m-d H:i:s'));
        $this->assertNull($products[0]->created);
        $this->assertEquals(false, $products[0]->attributes['discontinued']);
    }
}
