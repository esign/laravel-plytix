<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\CreateProductCategoryRequest;
use Esign\Plytix\Requests\CreateProductSubcategoryRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class CreateProductSubcategoryRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_create_product_subcategory_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'create-product-category.json', status: 200),
            MockResponseFixture::make(fixtureName: 'create-product-subcategory.json', status: 200),
        ]);

        $category = $plytix->send(new CreateProductCategoryRequest([
            'name' => 'Kitchen Sinks'
        ]))->dto()[0];
        
        $response = $plytix->send(new CreateProductSubcategoryRequest($category->id, [
            'name' => 'Apron'
        ]));

        $subcategory = $response->dto()[0];
        $mockClient->assertSent(CreateProductSubcategoryRequest::class);
        $this->assertEquals('Apron', $subcategory->name);
        $this->assertEquals('5cfa05273da03100019ba90e', $subcategory->parentsIds[0]);
        $this->assertEquals('apron', $subcategory->slug);
    }
}
