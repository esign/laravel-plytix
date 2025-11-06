<?php

namespace Esign\Plytix\Tests\Feature\Request\V2;

use PHPUnit\Framework\Attributes\Test;
use Esign\Plytix\Plytix;
use Esign\Plytix\Requests\V2\UpdateProductRequest;
use Esign\Plytix\Tests\Support\MockResponseFixture;
use Esign\Plytix\Tests\TestCase;
use Saloon\Http\Faking\MockClient;

class UpdateProductRequestTest extends TestCase
{
    #[Test]
    public function it_can_send_an_update_product_request(): void
    {
        $plytix = new Plytix();
        $mockClient = MockClient::global([
            MockResponseFixture::make(fixtureName: 'token.json', status: 200),
            MockResponseFixture::make(fixtureName: 'V2/update-product.json', status: 200),
        ]);

        $response = $plytix->send(new UpdateProductRequest(
            productId: '6797dcaeec3db037adfe04fd',
            payload: [
                'description' => '12345',
            ]
        ));

        $mockClient->assertSent(UpdateProductRequest::class);
        $product = $response->dto();
        $this->assertEquals('6797dcaeec3db037adfe04fd', $product->id);
        $this->assertEquals('2025-01-27 19:49:28', $product->modified->format('Y-m-d H:i:s'));
        $this->assertEquals('2025-01-27 19:21:18', $product->created->format('Y-m-d H:i:s'));
    }
}
