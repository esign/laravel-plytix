<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\CreateProductAttributeRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class CreateProductAttributeRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_create_product_attribute_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/create-product-attribute.json', status: 201),
        ]);

        $response = $plytix->send(new CreateProductAttributeRequest([
            'name' => 'In stock',
            'type_class' => 'BooleanAttribute',
        ]));

        $attribute = $response->dto()[0];

        $mockClient->assertSent(CreateProductAttributeRequest::class);
        $this->assertEquals('5d0b4ea525abd700016fc037', $attribute->id);
        $this->assertEquals('in_stock', $attribute->label);
        $this->assertEquals('In Stock', $attribute->name);
        $this->assertEquals('BooleanAttribute', $attribute->typeClass);
        $this->assertIsArray($attribute->groups);
        $this->assertCount(0, $attribute->groups);
    }
}
