<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\DataTransferObjects\Asset;
use Esign\Plytix\DataTransferObjects\Product;
use Esign\Plytix\DataTransferObjects\ProductCategory;
use Esign\Plytix\DataTransferObjects\ProductVariation;
use Esign\Plytix\DataTransferObjects\RelatedProduct;
use Esign\Plytix\DataTransferObjects\RelationshipInformation;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\ProductVariationsRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class ProductVariationRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_product_variations_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product-variations.json', status: 200),
        ]);

        $response = $plytix->send(new ProductVariationsRequest('5ec383c6421a5e26d9ac71b1'));

        $mockClient->assertSent(ProductVariationsRequest::class);
        $this->assertEquals('5ec383adf18d516fbbac718d', $response->json('data.0.id'));
        $this->assertEquals('1013', $response->json('data.0.sku'));
    }

    /** @test */
    public function it_can_create_a_dto_from_a_response_with_all_attributes()
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product-variations.json', status: 200),
        ]);

        $response = $plytix->send(new ProductVariationsRequest('5ec383c6421a5e26d9ac71b1'));
        $productVariations = $response->dto();

        $this->assertIsArray($productVariations);
        $this->assertCount(8, $productVariations);
        $this->assertInstanceOf(ProductVariation::class, $productVariations[0]);
        $this->assertEquals('5ec383adf18d516fbbac718d', $productVariations[0]->id);
        $this->assertEquals('1013', $productVariations[0]->sku);
        $this->assertEquals('Backpack Venice - Coco', $productVariations[0]->label);
        $this->assertEquals('Draft', $productVariations[0]->status);
        $this->assertEquals('2020-05-19 07:03:05', $productVariations[0]->modified->format('Y-m-d H:i:s'));
        $this->assertNull($productVariations[0]->created);
        // Attributes
        $this->assertIsArray($productVariations[0]->attributes);
        $this->assertEquals(false, $productVariations[0]->attributes['discontinued']);
        // Product categories
        $this->assertIsArray($productVariations[0]->categories);
        $this->assertInstanceOf(ProductCategory::class, $productVariations[0]->categories[0]);
        $this->assertEquals('5ec383adf18d516fbbac718d', $productVariations[0]->categories[0]->id);
        // Assets
        $this->assertIsArray($productVariations[0]->assets);
        $this->assertInstanceOf(Asset::class, $productVariations[0]->assets[0]);
        $this->assertEquals('5c483ee8eb9139000154dd5e', $productVariations[0]->assets[0]->id);
        // Relationships
        $this->assertIsArray($productVariations[0]->relationships);
        $this->assertNull($productVariations[1]->relationships);
        $this->assertInstanceOf(RelationshipInformation::class, $productVariations[0]->relationships[0]);
        $this->assertEquals('64ad0d69573a2e83cd38b146', $productVariations[0]->relationships[0]->relationshipId);
        $this->assertEquals('related_products', $productVariations[0]->relationships[0]->relationshipLabel);
        $this->assertIsArray($productVariations[0]->relationships[0]->relatedProducts);
        $this->assertInstanceOf(RelatedProduct::class, $productVariations[0]->relationships[0]->relatedProducts[0]);
        $this->assertEquals('5ec383c6421a5e26d9ac71b1', $productVariations[0]->relationships[0]->relatedProducts[0]->productId);
        $this->assertEquals(1, $productVariations[0]->relationships[0]->relatedProducts[0]->quantity);
    }
}
