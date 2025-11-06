<?php

namespace Esign\Plytix\Tests\Feature\Request\V2;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V2\CreateProductRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class CreateProductRequestTest extends TestCase
{
    #[Test]
    public function it_can_send_a_create_product_request()
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V2/create-product.json', status: 201),
        ]);

        $response = $plytix->send(new CreateProductRequest([
            'sku' => '12345',
            'label' => 'Black Kettle',
        ]));
        $product = $response->dto();

        $mockClient->assertSent(CreateProductRequest::class);
        $this->assertEquals('6797dcaeec3db037adfe04fd', $product->id);
        $this->assertEquals('2025-01-27 19:21:18', $product->created->format('Y-m-d H:i:s'));
        $this->assertEquals('2025-01-27 19:21:18', $product->modified->format('Y-m-d H:i:s'));
    }
}
