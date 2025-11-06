<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\DataTransferObjects\V1\ProductAttribute;
use Esign\Plytix\DataTransferObjects\V1\ProductAttributeGroup;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\ProductAttributeGroupSearchRequest;
use Esign\Plytix\Requests\V1\ProductAttributeSearchRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class ProductAttributeGroupSearchRequestTest extends TestCase
{
    #[Test]
    public function it_can_send_a_product_attirubte_group_search_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-attribute-group-search.json', status: 200),
        ]);

        $response = $plytix->send(new ProductAttributeGroupSearchRequest());

        $mockClient->assertSent(ProductAttributeGroupSearchRequest::class);
        $this->assertEquals('5d0baf02d4bf5513d19023f3', $response->json('data.0.id'));
        $this->assertEquals('Basic Information', $response->json('data.0.name'));
    }

    #[Test]
    public function it_can_create_a_dto_from_a_response()
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/product-attribute-group-search.json', status: 200),
        ]);

        $response = $plytix->send(new ProductAttributeGroupSearchRequest());
        $productAttributeGroups = $response->dto();

        $this->assertIsArray($productAttributeGroups);
        $this->assertCount(3, $productAttributeGroups);
        $this->assertInstanceOf(ProductAttributeGroup::class, $productAttributeGroups[0]);
        $this->assertEquals('5d0baf02d4bf5513d19023f3', $productAttributeGroups[0]->id);
        $this->assertEquals('Basic Information', $productAttributeGroups[0]->name);
        $this->assertEquals(0, $productAttributeGroups[0]->order);
        $this->assertIsArray($productAttributeGroups[0]->attributeLabels);
        $this->assertEquals('gtin', $productAttributeGroups[0]->attributeLabels[0]);
    }
}
