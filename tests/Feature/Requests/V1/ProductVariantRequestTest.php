<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use Esign\Plytix\DataTransferObjects\V1\Asset;
use Esign\Plytix\DataTransferObjects\V1\Product;
use Esign\Plytix\DataTransferObjects\V1\ProductCategory;
use Esign\Plytix\DataTransferObjects\V1\RelatedProduct;
use Esign\Plytix\DataTransferObjects\V1\RelationshipInformation;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\ProductVariantRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class ProductVariantRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_product_variants_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product-variants.json', status: 200),
        ]);

        $response = $plytix->send(new ProductVariantRequest('5ec383c6421a5e26d9ac71b1'));

        $mockClient->assertSent(ProductVariantRequest::class);
        $this->assertEquals('5ec383adf18d516fbbac718d', $response->json('data.0.id'));
        $this->assertEquals('1013', $response->json('data.0.sku'));
    }

    /** @test */
    public function it_can_create_a_dto_from_a_response_with_all_attributes()
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product-variants.json', status: 200),
        ]);

        $response = $plytix->send(new ProductVariantRequest('5ec383c6421a5e26d9ac71b1'));
        $productVariants = $response->dto();

        $this->assertIsArray($productVariants);
        $this->assertCount(8, $productVariants);
        $this->assertInstanceOf(Product::class, $productVariants[0]);
        $this->assertEquals('5ec383adf18d516fbbac718d', $productVariants[0]->id);
        $this->assertEquals('1013', $productVariants[0]->sku);
        $this->assertEquals('Backpack Venice - Coco', $productVariants[0]->label);
        $this->assertEquals('Draft', $productVariants[0]->status);
        $this->assertEquals('2020-05-19 07:03:05', $productVariants[0]->modified->format('Y-m-d H:i:s'));
        $this->assertNull($productVariants[0]->created);
        // Attributes
        $this->assertIsArray($productVariants[0]->attributes);
        $this->assertEquals(false, $productVariants[0]->attributes['discontinued']);
        // Product categories
        $this->assertIsArray($productVariants[0]->categories);
        $this->assertInstanceOf(ProductCategory::class, $productVariants[0]->categories[0]);
        $this->assertEquals('5ec383adf18d516fbbac718d', $productVariants[0]->categories[0]->id);
        // Assets
        $this->assertIsArray($productVariants[0]->assets);
        $this->assertInstanceOf(Asset::class, $productVariants[0]->assets[0]);
        $this->assertEquals('5c483ee8eb9139000154dd5e', $productVariants[0]->assets[0]->id);
        // Relationships
        $this->assertIsArray($productVariants[0]->relationships);
        $this->assertNull($productVariants[1]->relationships);
        $this->assertInstanceOf(RelationshipInformation::class, $productVariants[0]->relationships[0]);
        $this->assertEquals('64ad0d69573a2e83cd38b146', $productVariants[0]->relationships[0]->relationshipId);
        $this->assertEquals('related_products', $productVariants[0]->relationships[0]->relationshipLabel);
        $this->assertIsArray($productVariants[0]->relationships[0]->relatedProducts);
        $this->assertInstanceOf(RelatedProduct::class, $productVariants[0]->relationships[0]->relatedProducts[0]);
        $this->assertEquals('5ec383c6421a5e26d9ac71b1', $productVariants[0]->relationships[0]->relatedProducts[0]->productId);
        $this->assertEquals(1, $productVariants[0]->relationships[0]->relatedProducts[0]->quantity);
    }
}
