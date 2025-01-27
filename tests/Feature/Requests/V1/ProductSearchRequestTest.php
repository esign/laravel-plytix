<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use Esign\Plytix\DataTransferObjects\V1\Asset;
use Esign\Plytix\DataTransferObjects\V1\Product;
use Esign\Plytix\DataTransferObjects\V1\ProductCategory;
use Esign\Plytix\DataTransferObjects\V1\RelatedProduct;
use Esign\Plytix\DataTransferObjects\V1\RelationshipInformation;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\ProductSearchRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class ProductSearchRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_product_search_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-search.json', status: 200),
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
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-search-with-all-attributes.json', status: 200),
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
        // Attributes
        $this->assertIsArray($products[0]->attributes);
        $this->assertEquals(false, $products[0]->attributes['discontinued']);
        // Product categories
        $this->assertIsArray($products[0]->categories);
        $this->assertInstanceOf(ProductCategory::class, $products[0]->categories[0]);
        $this->assertEquals('5ec383adf18d516fbbac718d', $products[0]->categories[0]->id);
        // Assets
        $this->assertIsArray($products[0]->assets);
        $this->assertInstanceOf(Asset::class, $products[0]->assets[0]);
        $this->assertEquals('5c483ee8eb9139000154dd5e', $products[0]->assets[0]->id);
        // Relationships
        $this->assertIsArray($products[0]->relationships);
        $this->assertNull($products[1]->relationships);
        $this->assertInstanceOf(RelationshipInformation::class, $products[0]->relationships[0]);
        $this->assertEquals('64ad0d69573a2e83cd38b146', $products[0]->relationships[0]->relationshipId);
        $this->assertEquals('related_products', $products[0]->relationships[0]->relationshipLabel);
        $this->assertIsArray($products[0]->relationships[0]->relatedProducts);
        $this->assertInstanceOf(RelatedProduct::class, $products[0]->relationships[0]->relatedProducts[0]);
        $this->assertEquals('5ec383c6421a5e26d9ac71b1', $products[0]->relationships[0]->relatedProducts[0]->productId);
        $this->assertEquals(1, $products[0]->relationships[0]->relatedProducts[0]->quantity);
    }
}
