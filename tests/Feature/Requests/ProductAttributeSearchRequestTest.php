<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\DataTransferObjects\ProductAttribute;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\ProductAttributeSearchRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class ProductAttributeSearchRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_product_attributes_search_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product-attributes-search.json', status: 200),
        ]);

        $response = $plytix->send(new ProductAttributeSearchRequest());

        $mockClient->assertSent(ProductAttributeSearchRequest::class);
        $this->assertEquals('5c19ffda0b64d40460c50d1e', $response->json('data.0.id'));
        $this->assertEquals('Description', $response->json('data.0.name'));
    }

    /** @test */
    public function it_can_create_a_dto_from_a_response_with_all_attributes()
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product-attributes-search.json', status: 200),
        ]);

        $response = $plytix->send(new ProductAttributeSearchRequest());
        $productAttributes = $response->dto();
        
        $this->assertIsArray($productAttributes);
        $this->assertCount(10, $productAttributes);
        $this->assertEquals('5c19ffda0b64d40460c50d1e', $productAttributes[0]->id);
        $this->assertEquals('description', $productAttributes[0]->label);
        $this->assertEquals('Description', $productAttributes[0]->name);
        $this->assertIsArray($productAttributes[0]->groups);
        $this->assertCount(1, $productAttributes[0]->groups);
        $this->assertEquals('5c582a979e877f04666c649c', $productAttributes[0]->groups[0]);
    }

    /** @test */
    public function it_can_create_a_dto_from_a_paginated_response()
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product-attributes-search.json', status: 200),
        ]);

        $paginator = $plytix->paginate(new ProductAttributeSearchRequest());
        $productAttributes = $paginator->current()->dto();

        $this->assertIsArray($productAttributes);
        $this->assertCount(10, $productAttributes);
        $this->assertInstanceOf(ProductAttribute::class, $productAttributes[0]);
    }

    
}
