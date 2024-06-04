<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\CreateAssetRequest;
use Esign\Plytix\Requests\CreateAttributeRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class CreateAttributeRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_a_create_asset_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'create-attribute.json', status: 201),
        ]);

        $response = $plytix->send(new CreateAttributeRequest([
            'name' => 'In stock',
            'type_class' => 'BooleanAttribute',
        ]));
        
        $attribute = $response->dto()[0];

        $mockClient->assertSent(CreateAttributeRequest::class);
        $this->assertEquals('5d0b4ea525abd700016fc037', $attribute->id);
        $this->assertEquals('in_stock', $attribute->label);
    }
}
