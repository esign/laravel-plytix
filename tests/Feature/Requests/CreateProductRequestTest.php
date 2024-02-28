<?php

namespace Esign\Plytix\Tests\Feature\Requests;

use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\CreateProductRequest;
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
            MockResponseFixture::make(fixtureName: 'create-product.json', status: 201),
        ]);

        $response = $plytix->send(new CreateProductRequest([
            'sku' => '12345',
            'label' => 'Black Kettle',
        ]));

        $mockClient->assertSent(CreateProductRequest::class);
        $this->assertEquals('12345', $response->json('data.0.sku'));
        $this->assertEquals('Black Kettle', $response->json('data.0.label'));
    }
}
