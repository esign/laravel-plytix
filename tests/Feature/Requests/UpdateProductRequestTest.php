<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\UpdateProductRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class UpdateProductRequestTest extends TestCase
{
    /** @test */
    public function it_can_send_an_update_product_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'update-product.json', status: 200),
        ]);

        $response = $plytix->send(new UpdateProductRequest(
            productId: '5c4ed8002f0985001e233279',
            payload: [
            'sku' => '12345',
            'label' => 'Black Kettle',
        ]));

        $mockClient->assertSent(UpdateProductRequest::class);
        $this->assertEquals('12345', $response->json('data.0.sku'));
        $this->assertEquals('Black Kettle', $response->json('data.0.label'));
    }
}
