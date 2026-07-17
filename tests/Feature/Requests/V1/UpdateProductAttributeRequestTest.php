<?php

namespace Esign\Plytix\Tests\Feature\Request\V1;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V1\UpdateProductAttributeRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

final class UpdateProductAttributeRequestTest extends TestCase
{
    #[Test]
    public function it_can_send_an_update_product_attribute_request(): void
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V1/update-product-attribute.json', status: 200),
        ]);

        $response = $plytix->send(new UpdateProductAttributeRequest(
            productAttributeId: '5d0b4ea525abd700016fc037',
            payload: [
                'name' => 'new name',
            ]
        ));

        $attribute = $response->dto()[0];

        $mockClient->assertSent(UpdateProductAttributeRequest::class);
        $this->assertEquals('5d0b4ea525abd700016fc037', $attribute->id);
        $this->assertEquals('in_stock', $attribute->label);
        $this->assertEquals('new name', $attribute->name);
        $this->assertEquals('BooleanAttribute', $attribute->typeClass);
        $this->assertIsArray($attribute->groups);
        $this->assertCount(0, $attribute->groups);
    }
}
