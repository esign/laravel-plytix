<?php

namespace Esign\Plytix\Tests\Feature\Request\V2;

use Esign\Plytix\DataTransferObjects\V2\Asset;
use Esign\Plytix\DataTransferObjects\V2\Product;
use Esign\Plytix\DataTransferObjects\V2\ProductCategory;
use Esign\Plytix\DataTransferObjects\V2\RelatedProduct;
use Esign\Plytix\DataTransferObjects\V2\RelationshipInformation;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V2\ProductSearchRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class ProductSearchRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_product_search_request(): void
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V2/product-search.json', status: 200),
        ]);

        $response = $plytix->send(new ProductSearchRequest());

        $mockClient->assertSent(ProductSearchRequest::class);
        $this->assertEquals('6797dafb0f261cea58ec4e7f', $response->json('data.0.id'));
        $this->assertEquals('NSP-10003', $response->json('data.0.sku'));
    }

    /** @test */
    public function it_can_create_a_dto_from_a_response_with_all_attributes(): void
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V2/product-search-with-all-attributes.json', status: 200),
        ]);

        $response = $plytix->send(new ProductSearchRequest());

        $products = $response->dto();
        $this->assertIsArray($products);
        $this->assertCount(25, $products);
        $this->assertInstanceOf(Product::class, $products[0]);
        $this->assertEquals('6797dafb0f261cea58ec4e7f', $products[0]->id);
        $this->assertEquals('NSP-10003', $products[0]->sku);
        $this->assertEquals('Plytix Logo iPhone Case, Biodegradable, Black', $products[0]->label);
        $this->assertEquals('Completed', $products[0]->status);
        $this->assertEquals(4, $products[0]->numVariations);
        $this->assertEquals('2025-01-27 19:14:05', $products[0]->modified->format('Y-m-d H:i:s'));
        $this->assertEquals('2025-01-27 19:14:03', $products[0]->created->format('Y-m-d H:i:s'));
        // Attributes
        $this->assertIsArray($products[0]->attributes);
        $this->assertEquals(true, $products[0]->attributes['published']);
        // Product categories
        $this->assertIsArray($products[0]->categoryIds);
        $this->assertEquals('6797dae60f261cea58ec4db5', $products[0]->categoryIds[0]);
        // Assets
        $this->assertIsArray($products[0]->assetIds);
        $this->assertEquals('6797daeb0f261cea58ec4dd4', $products[0]->assetIds[0]);
        // Relationships
        $this->assertIsArray($products[0]->relationships);
        $this->assertIsArray($products[1]->relationships);
        $this->assertInstanceOf(RelationshipInformation::class, $products[0]->relationships[0]);
        $this->assertEquals('6797dae60f261cea58ec4db7', $products[0]->relationships[0]->relationshipId);
        $this->assertEquals('related_items', $products[0]->relationships[0]->relationshipLabel);
        $this->assertIsArray($products[0]->relationships[0]->relatedProducts);
        $this->assertInstanceOf(RelatedProduct::class, $products[0]->relationships[0]->relatedProducts[0]);
        $this->assertEquals('6797dafb0f261cea58ec4e83', $products[0]->relationships[0]->relatedProducts[0]->productId);
        $this->assertEquals(1, $products[0]->relationships[0]->relatedProducts[0]->quantity);
    }
}
