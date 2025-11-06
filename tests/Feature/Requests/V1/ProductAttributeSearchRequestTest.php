<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\DataTransferObjects\V1\ProductAttribute;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\ProductAttributeSearchRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class ProductAttributeSearchRequestTest extends TestCase
{
    #[Test]
    public function it_can_send_a_product_attirubte_search_request(): void
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-attribute-search.json', status: 200),
        ]);

        $response = $plytix->send(new ProductAttributeSearchRequest());

        $mockClient->assertSent(ProductAttributeSearchRequest::class);
        $this->assertEquals('5c582c9c9e877f04666c64ac', $response->json('data.0.id'));
        $this->assertEquals('additional_image_galery', $response->json('data.0.label'));
    }

    #[Test]
    public function it_can_create_a_dto_from_a_response(): void
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-attribute-search.json', status: 200),
        ]);

        $response = $plytix->send(new ProductAttributeSearchRequest());
        $productAttributes = $response->dto();

        $this->assertIsArray($productAttributes);
        $this->assertCount(82, $productAttributes);
        $this->assertInstanceOf(ProductAttribute::class, $productAttributes[0]);
        $this->assertEquals('5c582c9c9e877f04666c64ac', $productAttributes[0]->id);
        $this->assertEquals('additional_image_galery', $productAttributes[0]->label);
        $this->assertEquals('additional image galery', $productAttributes[0]->name);
        $this->assertEquals('MediaGalleryAttribute', $productAttributes[0]->typeClass);
        $this->assertEquals('MediaGalleryAttribute', $productAttributes[0]->filterType);
        $this->assertIsArray($productAttributes[0]->groups);
        $this->assertEquals('5c582a979e877f04666c649c', $productAttributes[0]->groups[0]);
    }
}
