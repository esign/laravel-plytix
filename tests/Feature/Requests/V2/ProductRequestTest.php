<?php

namespace Esign\Plytix\Tests\Feature\Request\V2;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\DataTransferObjects\V2\Product;
use Esign\Plytix\DataTransferObjects\V2\RelatedProduct;
use Esign\Plytix\DataTransferObjects\V2\RelationshipInformation;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V2\ProductRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

final class ProductRequestTest extends TestCase
{
    #[Test]
    public function it_can_send_a_product_request(): void
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V2/product.json', status: 200),
        ]);

        $response = $plytix->send(new ProductRequest('67348b7e2bbf7d289efd7984'));

        $mockClient->assertSent(ProductRequest::class);
        $this->assertEquals('67348b7e2bbf7d289efd7984', $response->json('data.0._id'));
        $this->assertEquals('red adventure mug S02223', $response->json('data.0.sku'));
    }

    #[Test]
    public function it_can_create_a_dto_from_a_response(): void
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V2/product.json', status: 200),
        ]);

        $response = $plytix->send(new ProductRequest('67348b7e2bbf7d289efd7984'));
        $product = $response->dto();

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('67348b7e2bbf7d289efd7984', $product->id);
        $this->assertEquals('red adventure mug S02223', $product->sku);
        $this->assertEquals('Adventure mug', $product->label);
        $this->assertEquals(0, $product->numVariations);
        $this->assertEquals('2024-11-13 12:12:20', $product->modified->format('Y-m-d H:i:s'));
        $this->assertEquals('2024-11-13 11:20:30', $product->created->format('Y-m-d H:i:s'));
        $this->assertEquals(['67191873b301fed08f12f7fb'], $product->categoryIds);
        $this->assertEquals(
            ['671b5ffcc0c8f0e5fd21b9b9', '671918ceb301fed08f12f804', '671918c4b301fed08f12f803'],
            $product->assetIds
        );
        $this->assertInstanceOf(RelationshipInformation::class, $product->relationships[0]);
        $this->assertEquals('64ad0d69573a2e83cd38b146', $product->relationships[0]->relationshipId);
        $this->assertEquals('related_products', $product->relationships[0]->relationshipLabel);
        $this->assertInstanceOf(RelatedProduct::class, $product->relationships[0]->linksTo[0]);
        $this->assertEquals('5ec383c6421a5e26d9ac71b1', $product->relationships[0]->linksTo[0]->productId);
    }
}
