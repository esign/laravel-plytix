<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\CreateProductRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class CreateProductRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_create_product_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/create-product.json', status: 201),
        ]);

        $response = $plytix->send(new CreateProductRequest([
            'sku' => '12345',
            'label' => 'Black Kettle',
        ]));
        $product = $response->dto()[0];

        $mockClient->assertSent(CreateProductRequest::class);
        $this->assertEquals('5c4ef3a9bedb5e000189befc', $product->id);
        $this->assertEquals('12345', $product->sku);
        $this->assertEquals('Black Kettle', $product->label);
        $this->assertEquals('Completed', $product->status);
        $this->assertEquals(0, $product->numVariations);
        $this->assertIsArray($product->attributes);
        $this->assertCount(2, $product->attributes);
        $this->assertEquals(['Blue'], $product->attributes['color_create_a_multiselect_type_attribute']);
        $this->assertIsArray($product->assets);
        $this->assertCount(0, $product->assets);
        $this->assertIsArray($product->categories);
        $this->assertCount(3, $product->categories);
        $this->assertEquals('Drinkware', $product->categories[1]->name);
    }
}
