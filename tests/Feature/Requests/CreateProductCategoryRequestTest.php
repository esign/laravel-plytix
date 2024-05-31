<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\CreateProductCategoryRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class CreateProductCategoryRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_create_product_category_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'create-product-category.json', status: 201),
        ]);

        $response = $plytix->send(new CreateProductCategoryRequest([
            'name' => 'Kitchen Sinks'
        ]));

        $category = $response->dto()[0];
        $mockClient->assertSent(CreateProductCategoryRequest::class);
        $this->assertEquals('Kitchen Sinks', $category->name);
        $this->assertEquals('5', $category->order);
        $this->assertEquals('kitchen-sinks', $category->slug);
    }
}
