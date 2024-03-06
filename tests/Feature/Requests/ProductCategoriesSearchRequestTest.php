<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\DataTransferObjects\ProductCategory;
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

    /** @test */
    public function it_can_create_a_dto_from_a_response_with_all_attributes()
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product-categories-search-with-all-attributes.json', status: 200),
        ]);

        $response = $plytix->send(new ProductCategoriesSearchRequest());
        $productCategories = $response->dto();

        $this->assertIsArray($productCategories);
        $this->assertCount(3, $productCategories);
        $this->assertInstanceOf(ProductCategory::class, $productCategories[0]);
        $this->assertEquals('5cf4d55eb694740001006ed1', $productCategories[0]->id);
        $this->assertEquals('Random Category 1', $productCategories[0]->name);
        $this->assertEquals('2024-03-05 12:28:32', $productCategories[0]->modified->format('Y-m-d H:i:s'));
        $this->assertEquals(0, $productCategories[0]->nChildren);
        $this->assertEquals('1', $productCategories[0]->order);
        $this->assertEquals([
            '5cf4d561b694740001006ed2',
            '5cf69f4d568c1c001c039c76',
        ], $productCategories[0]->parentsIds);
        $this->assertEquals([
            'Random Parent 1',
            'Random Parent 2',
            'Random Category 1',
        ], $productCategories[0]->path);
        $this->assertEquals('random-category-1', $productCategories[0]->slug);
    }

    /** @test */
    public function it_can_create_a_dto_from_a_paginated_response()
    {
        $plytix = new Plytix();
        MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'product-categories-search-with-all-attributes.json', status: 200),
        ]);

        $paginator = $plytix->paginate(new ProductCategoriesSearchRequest());
        $productCategories = $paginator->current()->dto();

        $this->assertIsArray($productCategories);
        $this->assertCount(3, $productCategories);
        $this->assertInstanceOf(ProductCategory::class, $productCategories[0]);
    }
}
